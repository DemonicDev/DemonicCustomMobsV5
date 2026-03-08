<?php

namespace DemonicCM\DemonicDev\utils;

use DemonicCM\DemonicDev\HumanLiving;
use DemonicCM\DemonicDev\Main;
use pocketmine\utils\TextFormat as tf;

class SpawnUtils
{
    /** NOT FINISHED */

    /** TODO: Transform this to $name, $position? */

    public static function customspawn($name, $sender){
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

    public static function humanspawn($name, $sender){
        $nodes = Main::getInstance()->getNodes();
        $hNode = $nodes["h"];
        if(!$hNode?->mobexists($name)){
            $sender->sendMessage(TF::RED . "Couldn't find the specified mob");
            return;
        }
        $loc = $sender->getLocation();
        $data = $hNode->getMobdata($name);
        $skin = SkinUtils::skinCalculate($data["skin"]);
        $mob = new HumanLiving($loc, $skin, $data);
        $mob->spawnToAll();
    }
}