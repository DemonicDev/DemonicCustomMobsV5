<?php

namespace DemonicCM\DemonicDev\AI;

use DemonicCM\DemonicDev\Main;
use matze\pathfinder\result\PathResult;
use matze\pathfinder\rule\default\EntitySizeRule;
use matze\pathfinder\rule\default\NeedsAirRule;
use matze\pathfinder\rule\default\NeedsWaterRule;
use pocketmine\math\Vector3;
use pocketmine\utils\TextFormat;
use matze\pathfinder\Pathfinder;
use matze\pathfinder\setting\Settings;
trait AiManager
{
    /** Important: AiManager Trait can only be applied to Entity */
    private ?object $aio = null;
    private ?object $pathfinder = null;
    private function initAi(){
        //code to get here an ai instance to run each tick?
        $aiclass = Main::getInstance()->getAi($this->ai);
        $this->aio = new $aiclass($this);
        if($this->aio?->needPathfinding) {
            $this->startPathFinder();
        }
    }
    private function startPathFinder()
    {
        /** Todo: code a basic ai, from that we get if we need Async, Sync or No Pathfinder */
        $settings = Settings::get()
            ->setPathSmoothing(false)
            ->setMaxTravelDistanceDown($this->aio::MAXTRAVELDOWN)
            ->setMaxTravelDistanceUp($this->aio::MAXTRAVELUP);
        $this->pathfinder = new Pathfinder(
            $this->setRules($this->aio::RULES)
        , $settings);
    }
    private function setRules(array $rawrules): array{
        $rules = [];
        foreach($rawrules as $rulearray){ //[rule, priority, ?data] //priority goes from 0 to 4 low to high
            switch($rulearray[0]){
                case "EntitySizeRule":
                    $rules[] = new EntitySizeRule($this->getSize(), $rulearray[1]);
                break;
                case "NeedsAirRule":
                    $rules[] = new NeedsAirRule($this->getSize(), $rulearray[1]);
                break;
                case "NeedsWaterRule":
                    $rules[] = new NeedsWaterRule($this->getSize(), $rulearray[1]);
                break;
            }
        }
        return $rules;
    }

    public function onUpdate(int $currentTick): bool{
        if($this->aio?->needPathfinding) {
            try {
                $this->aio->runAi();
            } catch (\Exception $exception) {
                Main::getInstance()->getLogger()->error($exception->getMessage());
                Main::getInstance()->getLogger()->error($exception->getCode());
                Main::getInstance()->getLogger()->error($exception->getTraceAsString());
                Main::getInstance()->getLogger()->error($exception->getLine());

            }
        }
        return parent::onUpdate($currentTick);
    }
    public function getSpeed(): float
    {
        return $this->speed;
    }

    public function getPath(Vector3 $to, float $timeout=0.2){
        return $this->pathfinder->findPath($this->getPosition()->asVector3(), $to, $this->getWorld(),$timeout);
    }
    public function getPathAsync(Vector3 $to,callable $closure, float $timeout=0.2, int $chunkCacheLimit = 64){
        $this->pathfinder->findPathAsync(
            $this->getPosition()->asVector3(),
            $to,
            $this->getWorld(),
            function(?PathResult $result) use ($closure): void{
                $closure($result);
            },
            $timeout,
            $chunkCacheLimit
        );
    }

}