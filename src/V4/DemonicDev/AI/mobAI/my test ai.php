<?php

declare(strict_types=1);

namespace DemonicCM\DemonicDev\AI\mobAI;

use DemonicCM\DemonicDev\AI\AICORE\algorithm\AlgorithmSettings;
use DemonicCM\DemonicDev\AI\AICORE\entity\navigator\Navigator;
use DemonicCM\DemonicDev\AI\AICORE\Pathfinder;
use pocketmine\entity\Location;
use pocketmine\entity\Human;
use pocketmine\event\entity\EntityDamageEvent;
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


class hostile extends Human {
    protected Navigator $navigator;
	
	public $dmgcounter;
	public $xy;
	public $zy;

    public function __construct(Location $location, Skin $skin, ?CompoundTag $nbt = null){
        $this->navigator = new Navigator($this, null, null,
            (new AlgorithmSettings())
                ->setTimeout(0.05)
                ->setMaxTicks(0)
        );
        parent::__construct($location, $skin);

        $this->setScale(1.8);
		$this->navigator->setSpeed(1.2);
		$this->setSize(new EntitySizeInfo(1.8, 1.8));
		$this->dmgcounter = 0;
		$this->setNameTagAlwaysVisible(true);
		$this->setMaxHealth(70);
		$this->setHealth(70);
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
				$this->broadcastAnimation(new HurtAnimation($player));
				$player->setHealth($player->getHealth() - 1.0);
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
	
	public function test(){}
    

}