<?php

namespace DemonicCM\DemonicDev;

use pocketmine\plugin\PluginBase;
use pocketmine\Server;


use pocketmine\entity\EntityFactory;
use pocketmine\entity\EntityDataHelper as Helper;
use pocketmine\world\World;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as tf;


use DemonicCM\DemonicDev\Commands\spawn;

class Main extends PluginBase{
	
	public static self $instance;
	 
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
		$this->saveResource("Moblist.yml", false);
		$this->saveResource("Config.yml", false);
		$this->Config = new Config($this->getDataFolder() . "Config.yml", Config::YAML);
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
		
	public function download_example(){
		if(!file_exists($this->getDataFolder() . "boar")){
			@mkdir($this->getDataFolder()."\\boar");
		}
		if(!@copy("https://raw.githubusercontent.com/DemonicDev/testentity/main/boar/boar.geo.json", $this->getDataFolder()."\\boar\\boar.geo.json")){
			$this->getServer()->getLogger()->info(tf::RED. " THERE WAS AN ERROR WITH DOWNLOADING THE EXSAMPLE MOB PACK [File(1/3)]"); 
			$this->getServer()->getLogger()->info(tf::GREEN. "PLS CHECK YOUR INTERNET CONNECTION AND RESTART THE SERVER"); 
		}
		$png = "boar.png";
		if(!@copy("https://raw.githubusercontent.com/DemonicDev/testentity/main/boar/boar.png", $this->getDataFolder()."\\boar\\".$png)){
			$this->getServer()->getLogger()->info(tf::RED. " THERE WAS AN ERROR WITH DOWNLOADING THE EXSAMPLE MOB PACK [File(2/3)]"); 
			$this->getServer()->getLogger()->info(tf::GREEN. "PLS CHECK YOUR INTERNET CONNECTION AND RESTART THE SERVER"); 
		}
		$Econfig = "boar.yml";
		if(!@copy("https://raw.githubusercontent.com/DemonicDev/testentity/main/boar/boar.yml", $this->getDataFolder()."\\boar\\".$Econfig)){
			$this->getServer()->getLogger()->info(tf::RED. " THERE WAS AN ERROR WITH DOWNLOADING THE EXSAMPLE MOB PACK [File(3/3)]"); 
			$this->getServer()->getLogger()->info(tf::GREEN. "PLS CHECK YOUR INTERNET CONNECTION AND RESTART THE SERVER"); 
		}
		$this->getServer()->getLogger()->info(tf::GREEN. "Downloaded all resource data"); 
	}
	
}