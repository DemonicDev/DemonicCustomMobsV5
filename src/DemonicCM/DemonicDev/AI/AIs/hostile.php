<?php

namespace DemonicCM\DemonicDev\AI\AIs;

use pocketmine\entity\Entity;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use your\plugin\old\mobAI\utils\mobdmgcalc as MobDamageCalculator;

class hostile
{

    const MAXTRAVELUP = 1;
    const MAXTRAVELDOWN = 2;

    const REACH = 3;

    const RULES = [
        ["EntitySizeRule", 3],

    ];

    //private const RECALC_INTERVAL = 20; //claudes suggestion
    private const RECALC_INTERVAL = 30;
    private Entity $caller;
    public bool $needPathfinding = true;
    public function __construct(Entity $caller)
    {
        $this->caller = $caller;
    }

    private $currentPath = [];
    private $pathIndex = 0;

    private ?Player $target = null;

    private function findTarget()
    {
        if ($this->target === null or $this->target->isCreative() or $this->target->isSpectator()) {
            $closest = null;
            $closestDist = PHP_FLOAT_MAX;
            foreach ($this->caller->getWorld()->getPlayers() as $player) {
                if($player->isCreative() or $player->isSpectator())continue; //skip those
                $dist = $this->caller->getPosition()->distanceSquared($player->getPosition());
                if ($dist < $closestDist) {
                    $closestDist = $dist;
                    $closest = $player;
                }
            }
            if ($closestDist <= 256) {
                $this->target = $closest;
            }else{
                $this->target = null;
            }
        }
    }
    private function recalculatePath(){
        if ($this->caller->ticksLived % self::RECALC_INTERVAL === 0) {
            $to = $this->target?->getPosition()->asVector3();
            if($to === null){
                $to = $this->caller->getPosition()->asVector3()->add(mt_rand(0,32) - 16, 0, mt_rand(0,32) - 16);
            }
            $this->caller->getPathAsync($to, function ($result) {
                if($result == null){
                    $this->currentPath = [];
                    return;
                }
                $this->currentPath = $result->getNodes();
                $this->pathIndex = 0;
            });
        }
    }

    private function walk(){
        if (empty($this->currentPath)) {
            return;
        }
        // Skip waypoints we've already passed
        if (!isset($this->currentPath[$this->pathIndex])) {
            $this->currentPath = []; // Path finished
            return;
        }

        $next = $this->currentPath[$this->pathIndex];
        $pos = $this->caller->getPosition();

        // Horizontal distance only — don't get stuck on Y
        $dx = $next->x - $pos->x;
        $dz = $next->z - $pos->z;
        $dist = sqrt($dx * $dx + $dz * $dz);

        if ($dist < 0.3) {
            // Close enough, advance to next waypoint
            $this->pathIndex++;
            return;
        }

        // Build motion vector
        $motion = new Vector3(
            ($dx / $dist) * $this->caller->getSpeed(),
            $this->caller->getMotion()->y, // Keep gravity
            ($dz / $dist) * $this->caller->getSpeed()
        );

        // Jump if next waypoint is higher
        if ($next->y > $pos->y + 0.5 && $this->caller->onGround) {
            //$motion = $motion->withComponents(null, 0.42, null); // Vanilla jump force claudes suggest
            $motion = $motion->withComponents(null, 0.5, null); // But we are in PMMP
        }

        $this->caller->setMotion($motion);
        // Face the direction of movement
        $this->caller->setRotation(
            rad2deg(atan2(-$dx, $dz)),
            $this->caller->getLocation()->pitch
        );
    }

    private function attack()
    {
        $tvec = $this->target->getPosition()->asVector3();
        if($this->caller->getPosition()->asVector3()->distanceSquared($tvec) < self::REACH){
            $this->attackTarget();
        }
    }

    private function attackTarget(){
        //here comes attack cooldown :skull:
        $ev = new EntityDamageByEntityEvent(
            $this->caller,
            $this->target,
            EntityDamageEvent::CAUSE_ENTITY_ATTACK,
        $this->caller->damage // later add an DamageCalculator for Armor etc
        );
        $this->target->attack($ev);
    }
    /**
     * @return void
     *
     * old attackEntity function!
     * public function attackEntity(Entity $player){
     * if($player->isCreative(true)) return;
     * $targetVector3 = $this->navigator->getTargetVector3();
     * if($this->attackDelay > 40 && $targetVector3->distanceSquared($this->targetposition) < 2){
     * $this->attackDelay = 0;
     * $damage = $this->dmg;
     *
     * $ev = new EntityDamageByEntityEvent($this, $player, EntityDamageEvent::CAUSE_ENTITY_ATTACK,
     * MobDamageCalculator::calculateFinalDamage($player, $damage));
     * $player->attack($ev);
     * }
     * }
     *
     */

    public function runai(){
        $this->findTarget();
        $this->recalculatePath();
        $this->walk();
        if($this->target instanceof Player) $this->attack();
    }
}