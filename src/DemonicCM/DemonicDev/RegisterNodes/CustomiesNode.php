<?php

namespace DemonicCM\DemonicDev\RegisterNodes;

use customiesdevs\customies\entity\CustomiesEntityFactory;
use DemonicCM\DemonicDev\CustomiesLiving;
use DemonicCM\DemonicDev\Main;
use pocketmine\entity\EntityFactory;
use pocketmine\utils\Config;
use your\plugin\old\mobAI\hostile;

class CustomiesNode
{
    private $main;
    private $config;
    private $mobdata = [];

    private array $aiMap = [
        "hostile" => hostile::class
    ];
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
    private function pseudoClass($name, $data)
    {
        $networkTypeId = $data["id"];
        $classname = "CM_" . $name;
        $base = CustomiesLiving::class;
        if (!class_exists($classname)) {
            eval(" 
                class {$classname} extends {$base} {
                    public static function getNetworkTypeId(): string {
                        return '{$networkTypeId}';
                    }
                }
            ");
        }
        return $classname;
    }
    public function mobexists($name){
        if(!class_exists("CM_" . $name) or !isset($this->mobdata[$name])){
            return null;
        }
        return "CM_" . $name;
    }
    private function registerAll(){
        $factory = EntityFactory::getInstance();
        foreach ($this->mobdata as $name => $data) {
            $classname = $this->pseudoClass($name, $data);
            var_dump($classname);
            CustomiesEntityFactory::getInstance()->registerEntity($classname, $data["id"]);
        }
    }

    public function getMobdata($name){
        return $this->mobdata[$name];
    }
}