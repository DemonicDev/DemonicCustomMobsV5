<?php

namespace DemonicCM\DemonicDev\RegisterNodes;

use DemonicCM\DemonicDev\Main;
use pocketmine\entity\EntityFactory;
use pocketmine\utils\Config;

class HumanNode
{
    private $main;

    public function __construct(Main $main){
        $this->main = $main;
    }

    private function init(){
        $this->main->saveResource("human_mobs.yml",false);
        $this->config = new Config($this->main->getDataFolder() . "human_mobs.yml", Config::YAML);
        $this->mobdata = $this->config->getAll(); //[name => data]
        var_dump($this->mobdata);
        $this->registerAll();
    }

    private function registerAll(){
        $factory = EntityFactory::getInstance();
        try{
            $factory->register(hostile::class, function(World $world, CompoundTag $tag) use($name, $data): Entity{
                return new $data["ai"](EntityDataHelper::parseLocation($tag, $world),  $tag);
            }, [$data["id"], $name]);
        }catch (\Exception $e){
            $this->main->getLogger()->error($e->getMessage());
        }
    }
}