<?php

declare(strict_types=1);

namespace DemonicCM\DemonicDev\MobData;

use DemonicCM\DemonicDev\MobData\skincalc\skincalc;
use DemonicCM\DemonicDev\AI\mobAI\{hostile};
use DemonicCM\DemonicDev\_resource_processor\mobdata_from_yml as mobdata;

class entity_data{#
		
	public function getCMSkin($mob){
		$skincalc = new skincalc();
		$skin = $skincalc->skinCalculate($mob);
		return $skin;
	}
	
	public function newhostile($loc, $skin, $mob){
		$mobdata = new mobdata();
		$cmdmg = $mobdata->getDamage($mob);
		$health = $mobdata->getHealth($mob);
		$speed = $mobdata->getSpeed($mob);
		$scale = $mobdata->getScale($mob);
		$ai = $mobdata->getAi($mob);
		
		switch($ai){
			case "hostile":
				return new hostile($loc, $skin, $cmdmg, $health, $speed, $scale);
			break;
		
		}
	}
    

}