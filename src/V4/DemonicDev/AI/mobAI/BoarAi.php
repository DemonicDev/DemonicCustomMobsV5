<?php

declare(strict_types=1);

namespace DemonicCM\DemonicDev\AI\mobAI;

use DemonicCM\DemonicDev\AI\AICORE\algorithm\AlgorithmSettings;
use DemonicCM\DemonicDev\AI\AICORE\entity\navigator\Navigator;
use DemonicCM\DemonicDev\AI\AICORE\Pathfinder;
use pocketmine\entity\Location;
use pocketmine\entity\Human;
use pocketmine\entity\Entity;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\entity\Skin;

use pocketmine\Server;
use pocketmine\math\Vector3;
use pocketmine\world\Position;

use pocketmine\entity\EntitySizeInfo;

use pocketmine\entity\animation\HurtAnimation;
use pocketmine\utils\TextFormat as tf;
use Throwable;
use function array_key_first;
use function intval;

use pocketmine\item\VanillaItems;

use DemonicCM\DemonicDev\AI\mobAI\utils\mobdmgcalc as MobDamageCalculator;


class hostile extends Human {
 protected Navigator $navigator;
 
 public int $dmg;
 public $attackDelay = 0;
 public $targetposition;
 	public $dmgcounter;
	public $xy;
	public $zy;
 
   public function __construct(Location $location, Skin $skin, $cmdmg, $health, $speed, $scale, ?CompoundTag $nbt = null){
        $this->navigator = new Navigator($this, null, null,
            (new AlgorithmSettings())
                ->setTimeout(0.05)
                ->setMaxTicks(0)
        );
        parent::__construct($location, $skin);
		$this->setCMDamage($cmdmg);
		$this->setMaxHealth($health);
		$this->setHealth($health);
		$this->navigator->setSpeed($speed);		
        $this->setScale($scale);
	#	$this->setSize(new EntitySizeInfo(1.8, 1.8));
		#	$this->setNameTagAlwaysVisible(true);
		$this->setNameTagAlwaysVisible(true);
		$this->dmgcounter = 0;
    }
		public function setCMDamage($dmg){
		$this->dmg = $dmg;
	}
	public function getDamage(){
		return $this->dmg;
	}

    public function onUpdate(int $currentTick): bool{
		$this->setNameTag("Lvl 1" . tf::EOL . " " . $this->getHealth() . "/" . $this->getMaxHealth());
		$target = Server::getInstance()->getOnlinePlayers()[array_key_first(Server::getInstance()->getOnlinePlayers())] ?? null;
		#if($target->isAdventure(true) or $target->isSurvival(true)){
			if($target === null){
				return parent::onUpdate($currentTick);
			}
			$position = $target->getPosition();
			$targetVector3 = $this->navigator->getTargetVector3();
			if(!$position->world->isInWorld(intval($position->x), intval($position->y), intval($position->z))){
                return parent::onUpdate($currentTick);
			}
			
			if($this->navigator->getTargetVector3() === null || $targetVector3->distanceSquared($position) > 1) {
				if($this->dmgcounter > 0){
					$this->dmgcounter = $this->dmgcounter - 1;
				}else{
					$this->navigator->setTargetVector3($position);
				}
			}

			try {
				$this->navigator->onUpdate();
			} catch (Throwable $e) {
				$this->flagForDespawn();
				Pathfinder::$instance->getLogger()->logException($e);
			}
			return parent::onUpdate($currentTick);
	}
	public function onCollideWithPlayer($player): void{
		if($player->isAdventure(true) or $player->isSurvival(true)){
			if($this->dmgcounter == 0){
				$this->attackEntity($player);
				$this->dmgcounter = 70;
				switch(mt_rand(1, 2)){
					case 1:
						$this->xy = - mt_rand(25, 50);
					break;
					case 2:
						$this->xy = + mt_rand(25, 50);
					break;
				}
				switch(mt_rand(1, 2)){
					case 1:
						$this->zy = - mt_rand(25, 50);
					break;
					case 2:
						$this->zy = + mt_rand(25, 50);
					break;
				}
				$position = $player->getPosition();
				$this->navigator->setTargetVector3($position->addVector(new Vector3($this->xy, 0, $this->zy)));
				$this->navigator->onUpdate();
			}		
		}
	}
	
	public function attack(EntityDamageEvent $source): void{
        parent::attack($source);
			switch(mt_rand(1, 2)){
				case 1:
					$this->xy = - mt_rand(25, 50);
				break;
				case 2:
					$this->xy = + mt_rand(25, 50);
				break;
			}
			switch(mt_rand(1, 2)){
				case 1:
					$this->zy = - mt_rand(25, 50);
				break;
				case 2:
					$this->zy = + mt_rand(25, 50);
				break;
			}
			$player = $source->getEntity();
			$position = $player->getPosition();
                $this->dmgcounter = 50;
				$this->navigator->setTargetVector3($position->addVector(new Vector3($this->xy, 0, $this->zy)));
				$this->navigator->onUpdate();
	}
	
		public function attackEntity(Entity $player){
		$targetVector3 = $this->navigator->getTargetVector3();
			// maybe this needs some rework ... as it should be calculated within the event class and take
			// mob's weapon into account. for now, i just add the damage from the weapon the mob wears
		//	$damage = $this->getDamage();
			$damage = $this->getDamage();

			$ev = new EntityDamageByEntityEvent($this, $player, EntityDamageEvent::CAUSE_ENTITY_ATTACK,
				MobDamageCalculator::calculateFinalDamage($player, $damage));
			$player->attack($ev);
	}
	
	public function getDrops() : array{
        $drops = $this->drops();
		return $drops;
	}
    

}