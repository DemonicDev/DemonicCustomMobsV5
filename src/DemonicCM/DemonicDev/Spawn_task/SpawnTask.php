<?php

namespace DemonicCM\DemonicDev\Spawn_task;

/*DemonicCM classes*/
use DemonicCM\DemonicDev\MobData\entity_data;
use DemonicCM\DemonicDev\Main;

class SpawnTask {

	public function Spawn($mob, $loc){
		/*get data*/
		$Mobdata = new entity_data();
		$skin = $Mobdata->getCMSkin($mob);
		/*spawn*/
		$entity = $Mobdata->newhostile($loc, $skin, $mob);
        if($entity === null){
            Main::getInsance()->getLogger()->Info("Warning");
            return false;        }
		$entity->spawnToAll();
	}
	




}