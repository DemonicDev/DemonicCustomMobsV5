<?php

namespace DemonicCM\DemonicDev;

use DemonicCM\DemonicDev\utils\DropUtils;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\Living;
use pocketmine\entity\Location;
use pocketmine\nbt\tag\CompoundTag;
use DemonicCM\DemonicDev\AI\AiManager;
class CustomiesLiving extends Living
{
    use AiManager;
    private string $name = "";
    private array $drops = [];

    private float $damage = 0;
    private float $health = 20;
    private float $speed = 0.7; # AI Speed unused rn

    public string $ai = "braindead";
    private ?EntitySizeInfo $sizeInfo = null;

    public function __construct(Location $location, $data ,?CompoundTag $nbt = null)
    {
        /*most important line!!! don't change*/
        $this->setcanSaveWithChunk(false);
        // fixes the cant use Compoundtag as array error
        /** Todo: find a workaround so we can store custom mobs in Chunks, but tbh am not the biggest fan of storing entities in worlds files */
        /** If people want that feature i will try to find a workaround, for now it should be fine */

        parent::__construct($location, $nbt);
        $options = ["name", "drops", "damage", "health", "speed", "ai"];
        foreach ($options as $option) {
            try{
                if(isset($data[$option])){
                    $this->$option = $data[$option];
                }
            }catch (\Exception $e){}
        }
        if(isset($data["size"])){
            $this->sizeInfo = new EntitySizeInfo($data["size"][0], $data["size"][1]);
        }
        if(isset($data["scale"])){
            $this->setScale($data["scale"]);
        }
        $this->setMaxHealth($this->health);
        $this->setHealth($this->health);
        $this->initAi();
    }

    protected function getInitialSizeInfo(): EntitySizeInfo
    {
        if($this->sizeInfo instanceof EntitySizeInfo) return $this->sizeInfo;
        return new EntitySizeInfo(1.0,1.0);
    }

    public static function getNetworkTypeId(): string
    {
        return "";
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDrops(): array{
        return DropUtils::ParseDrops($this->drops);
    }
}