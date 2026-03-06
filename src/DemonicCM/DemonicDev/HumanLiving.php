<?php

namespace DemonicCM\DemonicDev;

use DemonicCM\DemonicDev\AI\AiManager;
#use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\Human;
use pocketmine\entity\Location;
use pocketmine\entity\Skin;
use pocketmine\nbt\tag\CompoundTag;

class HumanLiving extends Human
{
    use AiManager;

    private array $drops = [];

    private float $damage = 0;
    private float $health = 20;
    private float $speed = 0.7; # AI Speed unused rn

    public string $ai = "braindead";
    public function __construct(Location $location, Skin $skin, $data, ?CompoundTag $nbt = null)
    {
        parent::__construct($location, $skin, $nbt);
        $options = ["name", "drops", "damage", "health", "speed", "ai"];
        foreach ($options as $option) {
            try{
                if(isset($data[$option])){
                    $this->$option = $data[$option];
                }
            }catch (\Exception $e){}
        }
        /** Is Size needed for Human Mobs? idk */
       # if(isset($data["size"])){
        #    $this->sizeInfo = new EntitySizeInfo($data["size"][0], $data["size"][1]);
        #}
        if(isset($data["scale"])){
            $this->setScale($data["scale"]);
        }
        $this->setMaxHealth($this->health);
        $this->setHealth($this->health);
        $this->initAi();

    }
}