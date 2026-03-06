<?php

namespace DemonicCM\DemonicDev\AI\AIs;

use pocketmine\entity\Entity;

class NoAi
{
    private Entity $baseEntity;

    public bool $needPathfinding = false;

    public function __construct(Entity $caller){
        $this->baseEntity = $caller;
    }

}