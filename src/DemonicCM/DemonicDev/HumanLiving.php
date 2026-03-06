<?php

namespace DemonicCM\DemonicDev;

use pocketmine\entity\Human;
use pocketmine\entity\Location;
use pocketmine\entity\Skin;
use pocketmine\nbt\tag\CompoundTag;

class HumanLiving extends Human
{
    public function __construct(Location $location, Skin $skin, ?CompoundTag $nbt = null)
    {
        parent::__construct($location, $skin, $nbt);
    }
}