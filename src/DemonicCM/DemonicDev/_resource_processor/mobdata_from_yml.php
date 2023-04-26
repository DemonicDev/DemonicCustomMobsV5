<?php

namespace DemonicCM\DemonicDev\_resource_processor;

use DemonicCM\DemonicDev\Main;
use pocketmine\utils\Config;

class mobdata_from_yml{
	
	public function getDamage($mob){
		Main::getInstance()->mob = new Config(Main::getInstance()->getDataFolder() . $mob . "/" . "$mob.yml", Config::YAML);
		return Main::getInstance()->mob->get("damage");
	}
	public function getHealth($mob){
		Main::getInstance()->mob = new Config(Main::getInstance()->getDataFolder() . $mob . "/" . "$mob.yml", Config::YAML);
		return Main::getInstance()->mob->get("health");
	}
		public function getSpeed($mob){
		Main::getInstance()->mob = new Config(Main::getInstance()->getDataFolder() . $mob . "/" . "$mob.yml", Config::YAML);
		return Main::getInstance()->mob->get("speed");
	}
	public function getScale($mob){
		Main::getInstance()->mob = new Config(Main::getInstance()->getDataFolder() . $mob . "/" . "$mob.yml", Config::YAML);
		return Main::getInstance()->mob->get("scale");
	}
	public function getAi($mob){
		Main::getInstance()->mob = new Config(Main::getInstance()->getDataFolder() . $mob . "/" . "$mob.yml", Config::YAML);
		return Main::getInstance()->mob->get("Ai");
	}
    public function getDrops($mob){
        Main::getInstance()->mob = new Config(Main::getInstance()->getDataFolder() . $mob . "/" . "$mob.yml", Config::YAML);
        return Main::getInstance()->mob->get("drops");
    }

	

	
}