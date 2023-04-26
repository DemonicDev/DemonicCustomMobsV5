<?php

namespace DemonicCM\DemonicDev\Spawn_task;

/*DemonicCM classes*/
use DemonicCM\DemonicDev\MobData\entity_data;

class SpawnTask {

	public function Spawn($mob, $loc){
		/*get data*/
		$Mobdata = new entity_data();
		$skin = $Mobdata->getCMSkin($mob);
		/*spawn*/
		$entity = $Mobdata->newhostile($loc, $skin, $mob);
		$entity->spawnToAll();
	}
	




}