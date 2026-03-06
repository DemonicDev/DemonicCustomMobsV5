<?php

namespace DemonicCM\DemonicDev\RegisterNodes;

use DemonicCM\DemonicDev\HumanLiving;
use DemonicCM\DemonicDev\Main;
use pocketmine\entity\Entity;
use pocketmine\entity\EntityDataHelper;
use pocketmine\entity\EntityFactory;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\utils\Config;
use pocketmine\world\World;

class HumanNode
{
    private $main;
    private $config;
    private $mobdata = [];
    private $moblist = [];

    public function __construct(Main $main){
        $this->main = $main;
        $this->init();
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
        foreach($this->mobdata as $name => $data) {
            try {
                $factory->register(HumanLiving::class, function (World $world, CompoundTag $tag) use ($name, $data): Entity {
                    return new HumanLiving(EntityDataHelper::parseLocation($tag, $world), $tag);
                }, ["demonicdev:human_" . $name, $name]);
                $this->moblist[] = $name;
            } catch (\Exception $e) {
                $this->main->getLogger()->error($e->getMessage());
            }
        }
        var_dump($this->moblist);
    }

    public function mobexists($name){
        return in_array($name, $this->moblist);
    }
    public function getMobdata($name){
        return $this->mobdata[$name];

    }
}