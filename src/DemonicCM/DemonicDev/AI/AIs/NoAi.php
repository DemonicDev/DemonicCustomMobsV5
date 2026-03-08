<?php

namespace DemonicCM\DemonicDev\AI\AIs;

use pocketmine\entity\Entity;

class NoAi
{
    public bool $needPathfinding = false;
    public function __construct(Entity $caller){}

}