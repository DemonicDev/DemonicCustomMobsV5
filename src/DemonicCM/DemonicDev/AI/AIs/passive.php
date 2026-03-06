<?php

namespace DemonicCM\DemonicDev\AI\AIs;

use pocketmine\entity\Entity;
use pocketmine\math\Vector3;

class passive{

    const MAXTRAVELUP = 1;
    const MAXTRAVELDOWN = 2;

    const RULES = [
        ["EntitySizeRule", 3],

    ];

    //private const RECALC_INTERVAL = 20; //claudes suggestion
    private const RECALC_INTERVAL = 200;
    private Entity $caller;
    public bool $needPathfinding = true;
    public function __construct(Entity $caller)
    {
        $this->caller = $caller;
    }

    private $currentPath = [];
    private $pathIndex = 0;

    public function runai(){

        // Recalculate path every N ticks
        if ($this->caller->ticksLived % self::RECALC_INTERVAL === 0) {
            $to = $this->caller->getPosition()->asVector3()->add(mt_rand(0,32) - 16, 0, mt_rand(0,32) - 16);
            $this->caller->getPathAsync($to, function ($result) {
                if($result == null){
                    $this->currentPath = [];
                    return;
                }
                $this->currentPath = $result->getNodes();
                $this->pathIndex = 0;
            });
        }
        /** Following logic was created with help of Claude, changed and bug fixed by me
         *
         * like Self::SPEED to $this->caller->getSpeed $this->motion to $this->caller->getMotion() going so on, since i wanted
         * the ai to be outside of the entity class so you can change AIs and later register Ais with plugins
         */
        //follow given path
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



}