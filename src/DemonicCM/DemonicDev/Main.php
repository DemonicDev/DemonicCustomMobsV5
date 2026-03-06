<?php

namespace DemonicCM\DemonicDev;

use DemonicCM\DemonicDev\AI\AIs\NoAi;
use DemonicCM\DemonicDev\AI\AIs\passive;
use DemonicCM\DemonicDev\Commands\spawn;
use DemonicCM\DemonicDev\RegisterNodes\{CustomiesNode, HumanNode};
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;


class Main extends PluginBase{
	
	public static self $instance;

    public Config $Config;

    public $Moblist;
    private ?object $cNode = null;
    private ?object $hNode = null;

    private array $Ais = [
        "default" => NoAi::class,
        "passive" => passive::class,
    ];

	public function onLoad(): void
	{
		self::$instance = $this;
	}
	
	public static function getInstance(): Main
    {
        return self::$instance;
    }

	public function onEnable() : void
	{
		 $this->getServer()->getCommandMap()->registerAll('Commands',[
            new spawn("cm-spawn","used to spawn custom mobs","/cm-spawn")
        ]);

        /**
         * Todo: add Code to fetch custom AIs like plugins for this plugin?
         */

		$this->saveResource("config.yml", false);
		$this->Config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
		if($this->Config->get("CustomiesNode")){
            $this->cNode = new CustomiesNode($this);
            $this->getLogger()->info("Customies Node Activated");
        }
        if($this->Config->get("HumanNode")){
            $this->hNode = new HumanNode($this);
            $this->getLogger()->info("Human Node Activated");
        }

	}

    public function getNodes(){
        return ["c" => $this->cNode,
                "h" => $this->hNode
                ];
    }
    public function getAi($aiName){
        if(array_key_exists($aiName, $this->Ais)){
            return $this->Ais[$aiName];
        }
        return $this->Ais["default"];
    }
	
}