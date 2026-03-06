<?php

namespace DemonicCM\DemonicDev\utils;

use DemonicCM\DemonicDev\Main;
use pocketmine\item\StringToItemParser;
class DropUtils
{
    public static function ParseDrops($rawDrops){
        $newDrops = [];
        foreach ($rawDrops as $rawDrop){
            try {
                if(mt_rand(1, 100) <= $rawDrop[3]){ //drop chance
                    $item = StringToItemParser::getInstance()->parse($rawDrop[0]);
                    $item->setCount(mt_rand($rawDrop[1], $rawDrop[2]));
                    $newDrops[] = $item;
                }
            }catch (\Exception $exception){
                Main::getInstance()->getLogger()->error("Invalid Drop: " . strval($rawDrop));
                Main::getInstance()->getLogger()->error("$exception");
            }
        }
        return $newDrops;
    }

}