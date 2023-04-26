<?php

namespace DemonicCM\DemonicDev\_resource_processor;

use DemonicCM\DemonicDev\Main;

class Moblist{
	
$items = $config->getNested($args[1].".items");
foreach($items as $item) {
    if($item !== $args[2]) {
        $config->setNested($args[1].".items", $args[2]);
        $config->save();
    }
}

	
}