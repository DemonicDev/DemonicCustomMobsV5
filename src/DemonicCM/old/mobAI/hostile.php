<?php

declare(strict_types=1);

namespace DemonicCM\old\mobAI;
/** We will swap to virion based pathfinding */
/*
use pathfinder\algorithm\AlgorithmSettings;
use pathfinder\entity\navigator\Navigator;
*/

use DemonicCM\DemonicDev\Main;
use DemonicCM\old\mobAI\utils\mobdmgcalc as MobDamageCalculator;
use pocketmine\entity\Entity;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\Living;
use pocketmine\entity\Location;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\player\Player;
use Throwable;
use function intval;


class hostile extends Living {
#class hostile extends Human {
	#protected Navigator $navigator;
 
	public int $dmg;
	public $attackDelay = 0;
	public $targetposition;

    public array $drops;
    public function getName(): string
    {
        return "hostile";
        // TODO: Implement getName() method.
    }
    public function getInitialSizeInfo(): EntitySizeInfo
    {
        return new EntitySizeInfo(1.0,0.7);
    }

    public static function getNetworkTypeId(): string
    {
        return "test";
    }

    public function __construct(Location $location, ?CompoundTag $nbt = null){
   #public function __construct(Location $location, Skin $skin, $cmdmg, $health, $speed, $scale, $drops, ?CompoundTag $nbt = null){
       parent::__construct($location,  $nbt);
       #parent::__construct($location, $skin, $nbt);
        /** here comes implementation for other pathfinder */
       /*
       $this->navigator = new Navigator($this, null, null,
            (new AlgorithmSettings())
                ->setTimeout(0.001)
                ->setMaxTicks(0)
        );
       */

		/*most important line!!! don't change*/
        $this->setcanSaveWithChunk(false);
		/*mobdata*/
        #$this->dmg = $cmdmg;
		#$this->setMaxHealth($health);
		#$this->setHealth($health);
		//$this->navigator->setSpeed($speed);
        #$this->setScale($scale);
        #$this->drops = $drops;
	#	$this->setSize(new EntitySizeInfo(1.8, 1.8));
	#	$this->setNameTagAlwaysVisible(true);
    }

    public function onUpdate(int $currentTick): bool{
       return parent::onUpdate($currentTick);
       /** skip this part since it throws errors at this point! */
		$this->attackDelay += 1;
		$pos = $this->getLocation()->asVector3();
		$target = $this->getWorld()->getNearestEntity($pos, 24, Player::class, false);
        if($target === null or $target->isCreative(true) or $target->isSpectator(true)){
			return parent::onUpdate($currentTick);
		}
        $position = $target->getPosition();
        $targetVector3 = $this->navigator->getTargetVector3();
		$this->targetposition = $position;
        if(!$position->world->isInWorld(intval($position->x), intval($position->y), intval($position->z))){
            return parent::onUpdate($currentTick);
        }

        if($this->navigator->getTargetVector3() === null || $targetVector3->distanceSquared($position) > 1) {
            $this->navigator->setTargetVector3($position);
        }
		try {
			$this->navigator->onUpdate();
		} catch (Throwable $e) {
			$this->flagForDespawn();
            /** use instead Main i guess? */
            //Pathfinder::$instance->getLogger()->logException($e);
            Main::$instance->getLogger()->logException($e);
		}
        return parent::onUpdate($currentTick);
    }
	public function onCollideWithPlayer($player): void{
		$this->attackEntity($player);
	}
	/** attackEntity function from zombie from revivalpmmp\PureEntities */
	public function attackEntity(Entity $player){
		if($player->isCreative(true)) return;
		$targetVector3 = $this->navigator->getTargetVector3();
		if($this->attackDelay > 40 && $targetVector3->distanceSquared($this->targetposition) < 2){
			$this->attackDelay = 0;
			$damage = $this->dmg;

			$ev = new EntityDamageByEntityEvent($this, $player, EntityDamageEvent::CAUSE_ENTITY_ATTACK,
				MobDamageCalculator::calculateFinalDamage($player, $damage));
			$player->attack($ev);
		}
	}
	
	public function getDrops() : array{
		return $this->drops;
	}

}