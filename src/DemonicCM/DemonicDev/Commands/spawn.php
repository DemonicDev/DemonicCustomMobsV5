<?php

namespace DemonicCM\DemonicDev\Commands;

/*Pocketmine classes*/
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\console\ConsoleReader;
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
        $this->setPermission("spawn"); //Permission needed for pmmp5
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
		$nodes = Main::getInstance()->getNodes();
        $cNode = $nodes["c"];
        $class = $cNode->mobexists($arg);
        if($class == null){
            $sender->sendMessage(TF::RED . "Couldn't find the specified mob");
            return;
        }
        $loc = $sender->getLocation();
        $mob = new $class($loc);
        $mob->spawnToAll();
        var_dump($mob::getNetworkTypeId());
        #$spawntask = new SpawnTask();
        #$spawntask->Spawn($class, $loc);
	}

}
