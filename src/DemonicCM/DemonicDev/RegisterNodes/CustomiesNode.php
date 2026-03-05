<?php

namespace DemonicCM\DemonicDev\RegisterNodes;

use DemonicCM\DemonicDev\AI\mobAI\hostile;
use customiesdevs\customies\entity\CustomiesEntityFactory;
use pocketmine\entity\Entity;
use pocketmine\entity\EntityDataHelper;
use pocketmine\entity\EntityFactory;
use pocketmine\entity\Location;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\utils\Config;
use DemonicCM\DemonicDev\Main;
use pocketmine\world\World;

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
        $ai = $data["ai"];
        $aiclass = $this->aiMap[$ai] ?? null;
        if($aiclass === null){
            throw new \RuntimeException("unknown ai: {ai}");
        }
        $classname = "CM_" . $name;
        if (!class_exists($classname)) {
            eval(" 
                class {$classname} extends {$aiclass} {
                    public static function getNetworkTypeId(): string {
                        return '{$networkTypeId}';
                    }
                }
            ");
        }
        return $classname;
    }
    private function registerAll(){
        $factory = EntityFactory::getInstance();
        foreach ($this->mobdata as $name => $data) {
            $classname = $this->pseudoClass($name, $data);
            CustomiesEntityFactory::getInstance()->registerEntity($classname, $data["id"]);
            /**  this would be HumanNodeWay btw...
            try{
                $factory->register(hostile::class, function(World $world, CompoundTag $tag) use($name, $data): Entity{
                    return new $data["ai"](EntityDataHelper::parseLocation($tag, $world),  $tag);
                }, [$data["id"], $name]);
            }catch (\Exception $e){
                $this->main->getLogger()->error($e->getMessage());
            }*/
        }
    }
}