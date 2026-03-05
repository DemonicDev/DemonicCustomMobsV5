<?php

namespace DemonicCM\DemonicDev\RegisterNodes;

use DemonicCM\DemonicDev\Main;

class HumanNode
{
    private $main;

    public function __construct(Main $main){
        $this->main = $main;
    }
}