<?php

namespace DemonicCM\DemonicDev;

use DemonicCM\DemonicDev\AI\mobAI\hostile;
use pocketmine\entity\Human;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;


use pocketmine\entity\EntityFactory;
use pocketmine\entity\EntityDataHelper;
use pocketmine\world\World;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as tf;
use pocketmine\entity\Skin;


use DemonicCM\DemonicDev\Commands\spawn;

class Main extends PluginBase{
	
	public static self $instance;
    public $Config;
    public $Moblist;
	 
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
		$this->saveResource("moblist.yml", false);
		$this->saveResource("Config.yml", false);
		$this->Config = new Config($this->getDataFolder() . "Config.yml", Config::YAML);
        $this->registerAll();
		$exampledownload = $this->Config->get("download");
		if($exampledownload === true){
			if(!file_exists($this->getDataFolder()."\\boar\\boar.geo.json") or !file_exists($this->getDataFolder()."\\boar\\boar.png") or !file_exists($this->getDataFolder()."\\boar\\boar.yml")){
				$this->getServer()->getLogger()->info(tf::YELLOW. "We will now install the needed data from github");
				$this->download_example();
			}
		}elseif($exampledownload === false){
			$this->getServer()->getLogger()->info(tf::GREEN. "at Config.yml 'download:' was set to false, so we wont download anything");
		}else{
			$this->getServer()->getLogger()->info(tf::RED. "an error accured at 'Config.yml'... ");
			$this->getServer()->getLogger()->info(tf::RED. "at 'download:' choose between 'true' or 'false'");
		}

	}
    public function registerAll(): void{
        $factory = EntityFactory::getInstance();
        $factory->register(hostile::class, function(World $world, CompoundTag $tag): hostile{
            var_dump($tag);
            return new hostile(EntityDataHelper::parseLocation($tag, $world),  $tag);
        }, ["demonicdev:custom", "cHuman"]);
    }
	
}