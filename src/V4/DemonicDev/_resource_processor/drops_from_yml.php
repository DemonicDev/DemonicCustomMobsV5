<?php

namespace DemonicCM\DemonicDev\_resource_processor;

use DemonicCM\DemonicDev\Main;
use pocketmine\item\Item;
use DemonicCM\DemonicDev\_resource_processor\mobdata_from_yml as mobdata;

class drops_from_yml{
	
	public function getDropsArray($mob){
		$mobdata = new mobdata();
		$dropdata = $mobdata->getDrops($mob);	
		$drops = [];
		foreach($dropdata as $rawdata){
			
			$drops[] = $rawdata;
		}
		return $drops;
	}

	
}