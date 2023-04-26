<?php

declare(strict_types=1);

namespace DemonicCM\DemonicDev\AI\mobAI;

use pathfinder\algorithm\AlgorithmSettings;
use pathfinder\entity\navigator\Navigator;
use pocketmine\entity\Location;
use pocketmine\entity\Human;
use pocketmine\entity\Entity;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\entity\Skin;
use pocketmine\player\Player;
use pocketmine\math\Vector3;
use pocketmine\world\Position;

use pocketmine\entity\EntitySizeInfo;

use pocketmine\entity\animation\HurtAnimation;
use Throwable;
use function array_key_first;
use function intval;


use DemonicCM\DemonicDev\AI\mobAI\utils\mobdmgcalc as MobDamageCalculator;
use DemonicCM\DemonicDev\Main;


class passive extends Human {
	protected Navigator $navigator;
 
	public int $dmg;

    public array $drops;

    public $position;

    public $ticks_to_new_target = 0;
 
   public function __construct(Location $location, Skin $skin, $cmdmg, $health, $speed, $scale, $drops, ?CompoundTag $nbt = null){
        $this->navigator = new Navigator($this, null, null,
            (new AlgorithmSettings())
                ->setTimeout(0.001)
                ->setMaxTicks(0)
        );
        parent::__construct($location, $skin);
		/*most important line!!! don't change*/
        $this->setcanSaveWithChunk(false);
		/*mobdata*/
        $this->dmg = $cmdmg;
		$this->setMaxHealth($health);
		$this->setHealth($health);
		$this->navigator->setSpeed($speed);
        //$this->navigator->setTargetVector3($this->getLocation()->asVector3());
        $this->setScale($scale);
        $this->drops = $drops;
	#	$this->setSize(new EntitySizeInfo(1.8, 1.8));
	#	$this->setNameTagAlwaysVisible(true);
    }

    public function onUpdate(int $currentTick): bool{
       $this->ticks_to_new_target -= 1;
		$position = $this->getLocation()->asVector3();
        if($position == $this->position){
            $this->getNewTargetPos($position);
        }else {
            $this->position = $position;
        }
        $targetVector3 = $this->navigator->getTargetVector3();
       /** if(!$position->world->isInWorld(intval($position->x), intval($position->y), intval($position->z))){
            return parent::onUpdate($currentTick);
        }*/

        if($this->navigator->getTargetVector3() === null || $targetVector3->distanceSquared($position) < 2) {
            $this->getNewTargetPos($position);
        }
        if($this->ticks_to_new_target <= 0){
            $this->getNewTargetPos($position);
        }
		try {
			$this->navigator->onUpdate();
		} catch (Throwable $e) {
			$this->flagForDespawn();
            /** use instead Main i guess?*/
            //Pathfinder::$instance->getLogger()->logException($e);
            Main::$instance->getLogger()->logException($e);
		}
        return parent::onUpdate($currentTick);
    }

    public function getNewTargetPos($position){
       $this->ticks_to_new_target = 240;
        switch(mt_rand(1, 2)){
            case 1:
                $this->xy = - mt_rand(16, 32);
                break;
            case 2:
                $this->xy = + mt_rand(16, 32);
                break;
        }
        switch(mt_rand(1, 2)) {
            case 1:
                $this->zy = -mt_rand(16, 32);
                break;
            case 2:
                $this->zy = +mt_rand(16, 32);
                break;
        }
        $newposition = $position->addVector(new Vector3($this->xy, 0, $this->zy));
        $this->position = $newposition;
        $this->navigator->setTargetVector3($newposition);
        $this->navigator->onUpdate();
    }
	public function getDrops() : array{
		return $this->drops;
	}

}