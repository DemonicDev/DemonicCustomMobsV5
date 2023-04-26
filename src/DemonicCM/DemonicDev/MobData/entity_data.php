<?php

declare(strict_types=1);

namespace DemonicCM\DemonicDev\MobData;

use DemonicCM\DemonicDev\MobData\skincalc\skincalc;
use DemonicCM\DemonicDev\AI\mobAI\{hostile, passive};
use DemonicCM\DemonicDev\_resource_processor\mobdata_from_yml as mobdata;
use DemonicCM\DemonicDev\Main;

use pocketmine\item\ItemFactory;
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
        $drops = $this->CustomDrops_Handler($mobdata->getDrops($mob));
		switch($ai){
			case "hostile":
				return new hostile($loc, $skin, $cmdmg, $health, $speed, $scale, $drops);
			break;
            case "passive":
                return new passive($loc, $skin, $cmdmg, $health, $speed, $scale, $drops);
            break;
            default:
                return null;
            break;
		}
	}

    public function CustomDrops_Handler($droparray){
        $drops = [];
        if(is_array($droparray)) {
            foreach ($droparray as $drop) {
                if ($this->probability_calculator($drop[4]) === true) {
                    $id = $drop[0];
                    $meta = $drop[1];
                    $Amount_min = $drop[2];
                    $Amount_Max = $drop[3];
                    $drops[] = ItemFactory::getInstance()->get($id, $meta, mt_rand($Amount_min, $Amount_Max));
                }
            }
        }
        return $drops;
    }

    public function probability_calculator($percentage){
        //i hope i am not dumb with mt_rand or %
        Main::getInstance()->getLogger()->Info("$percentage");
        if($percentage == 100 or mt_rand(1, 10000) % 100 <= $percentage){
            return true;
        }
        return false;
    }

}