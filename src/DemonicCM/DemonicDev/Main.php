<?php

namespace DemonicCM\DemonicDev;

use DemonicCM\DemonicDev\Commands\spawn;
use DemonicCM\DemonicDev\RegisterNodes\{CustomiesNode, HumanNode};
use DemonicCM\old\mobAI\hostile;
use pocketmine\entity\EntityDataHelper;
use pocketmine\entity\EntityFactory;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\world\World;


class Main extends PluginBase{
	
	public static self $instance;
    public $Config;
    public $Moblist;
    private $cNode = null;
    private $hNode = null;
	public function onLoad(): void
	{
		self::$instance = $this;
	}
	
	public static function getInstance(): Main
    {
        return self::$instance;
    }
	
	public function onEnable() : void
	{
		 $this->getServer()->getCommandMap()->registerAll('Commands',[
            new spawn("cm-spawn","used to spawn custom mobs","/cm-spawn")
        ]);

        /**
         * Todo: add Code to fetch custom AIs like plugins for this plugin?
         */

		$this->saveResource("config.yml", false);
		$this->Config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
		if($this->Config->get("CustomiesNode")){
            $this->cNode = new CustomiesNode($this);
            $this->getLogger()->info("Customies Node Activated");
        }
        if($this->Config->get("HumanNode")){
            $this->hNode = new HumanNode($this);
            $this->getLogger()->info("Human Node Activated");
        }

	}

    public function getNodes(){
        return ["c" => $this->cNode,
                "h" => $this->hNode
                ];
    }
    public function registerAll(): void{
        $factory = EntityFactory::getInstance();
        $factory->register(hostile::class, function(World $world, CompoundTag $tag): hostile{
            var_dump($tag);
            return new hostile(EntityDataHelper::parseLocation($tag, $world),  $tag);
        }, ["demonicdev:custom", "cHuman"]);
    }
	
}