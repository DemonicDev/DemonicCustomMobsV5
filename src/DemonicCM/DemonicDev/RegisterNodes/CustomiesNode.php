<?php

namespace DemonicCM\DemonicDev\RegisterNodes;

use DemonicCM\DemonicDev\AI\mobAI\hostile;
use pocketmine\entity\EntityDataHelper;
use pocketmine\entity\EntityFactory;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\utils\Config;
use DemonicCM\DemonicDev\Main;
use pocketmine\world\World;

class CustomiesNode
{
    private $main;
    private $config;

    private $mobdata = [];
    public function __construct(Main $main){
        $this->main = $main;
        $this->init();
    }
    private function init(){
        $this->main->saveResource("customies_mobs.yml",false);
        $this->config = new Config($this->main->getDataFolder() . "customies_mobs.yml", Config::YAML);
        $this->mobdata = $this->config->getAll(); //[name => data]
        var_dump($this->mobdata);
        $this->registerAll();
    }

    private function registerAll(){
        $factory = EntityFactory::getInstance();

        foreach ($this->mobdata as $name => $data) {
            try{
                $factory->register(hostile::class, function(World $world, CompoundTag $tag) use($name, $data){
                    return new $data["ai"](EntityDataHelper::parseLocation($tag, $world),  $tag);
                }, [$data["id"], $name]);
            }catch (\Exception $e){
                $this->main->getLogger()->error($e->getMessage());
            }
        }
    }
}