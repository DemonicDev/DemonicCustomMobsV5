<?php

declare(strict_types=1);

namespace DemonicCM\DemonicDev\AI\AICORE\algorithm\validator;

use DemonicCM\DemonicDev\AI\AICORE\algorithm\Algorithm;
use pocketmine\math\Vector3;

abstract class Validator {
    abstract public function isSafeToStandAt(Algorithm $algorithm, Vector3 $vector3): bool;
}