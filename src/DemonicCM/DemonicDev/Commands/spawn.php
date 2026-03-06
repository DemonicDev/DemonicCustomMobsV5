<?php

namespace DemonicCM\DemonicDev\Commands;

/*Pocketmine classes*/

use DemonicCM\DemonicDev\HumanLiving;
use DemonicCM\DemonicDev\utils\SkinUtils;
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
			if(!isset($args[1])) {
				$sender->sendMessage("Please type '/cm-spawn help'.");
				return true;
			}
            switch ($args[0]) {
                case 'help':
                    $sender->sendMessage(TF::GREEN."cm-spawn [node] [name]");
                break;
                case "custom":
                    $this->customspawn($args[1], $sender);
            }
		}
	}

    public function customspawn($name, $sender){
        $nodes = Main::getInstance()->getNodes();
        $cNode = $nodes["c"];
        $class = $cNode?->mobexists($name);
        if($class == null){
            $sender->sendMessage(TF::RED . "Couldn't find the specified mob");
            return;
        }
        $loc = $sender->getLocation();
        $data = $cNode->getMobdata($name);
        $data["name"] = $name;
        $mob = new $class($loc, $data);
        $mob->spawnToAll();
    }
    public function humanspawn($name, $sender){
        $nodes = Main::getInstance()->getNodes();
        $hNode = $nodes["h"];
        if(!$hNode?->mobexists($name)){
            $sender->sendMessage(TF::RED . "Couldn't find the specified mob");
            return;
        }
        $loc = $sender->getLocation();
        $data = $hNode->getMobdata($name);
        $skin = SkinUtils::skinCalculate($name);
        $mob = new HumanLiving($loc, $skin, $data);
        $mob->spawnToAll();
    }
}
