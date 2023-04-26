<?php

declare(strict_types=1);

namespace DemonicCM\DemonicDev\AI\AICORE\entity\navigator\handler;

use DemonicCM\DemonicDev\AI\AICORE\algorithm\path\PathPoint;
use DemonicCM\DemonicDev\AI\AICORE\entity\navigator\Navigator;

abstract class MovementHandler {
    protected float $gravity = 0.08;

    public function getGravity(): float{
        return $this->gravity;
    }

    public function setGravity(float $gravity): void{
        $this->gravity = $gravity;
    }

    abstract public function handle(Navigator $navigator, PathPoint $pathPoint): void;
}