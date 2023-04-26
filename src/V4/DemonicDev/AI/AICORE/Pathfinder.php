<?php

declare(strict_types=1);

namespace DemonicCM\DemonicDev\AI\AICORE;

use pocketmine\data\bedrock\EntityLegacyIds;
use pocketmine\entity\EntityDataHelper;
use pocketmine\entity\EntityFactory;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\world\World;

class Pathfinder{
    public static Pathfinder $instance;

    protected function onEnable(): void{
        self::$instance = $this;


        EntityFactory::getInstance()->register(TestEntity::class, function(World $world, CompoundTag $nbt) : TestEntity{
            return new TestEntity(EntityDataHelper::parseLocation($nbt, $world), $nbt);
        }, ["TestEntity"], EntityLegacyIds::VILLAGER);
    }
}