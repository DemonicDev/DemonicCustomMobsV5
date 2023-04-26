<?php

namespace DemonicCM\DemonicDev\Commands;

/*Pocketmine classes*/
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat as tf;
use pocketmine\player\Player;
//use pocketmine\Server;

use pocketmine\utils\Config;

/*DemonicCM Classes*/
use DemonicCM\DemonicDev\Main;
use DemonicCM\DemonicDev\Spawn_task\SpawnTask;

class spawn extends Command{

	public function __construct(string $name, Translatable|string $description = "", Translatable|string|null $usageMessage = null, array $aliases = []){
        parent::__construct($name, $description, $usageMessage, $aliases);
    }
	
	public function execute(CommandSender $sender, string $commandLabel, array $args){
        if(!$sender instanceof Player){
            $sender->sendMessage("please execute this command in game");
        }else{
			if(!isset($args[0])) {
				$sender->sendMessage("Please type '/cm-spawn help'.");
				return true;
			}
			$arg = array_shift($args);
			$this->Mobspawn($arg, $sender);
		}
	}
	
	public function Mobspawn($arg, $sender){
		Main::getInstance()->Moblist = new Config(Main::getInstance()->getDataFolder() . "Moblist.yml", Config::YAML);
		$mobarry = Main::getInstance()->Moblist->get("mobs");
		foreach($mobarry as $mob){
			if($mob == $arg){
				$loc = $sender->getLocation();
				$spawntask = new SpawnTask();
				$spawntask->Spawn($mob, $loc);
			}
		}
	}

}
