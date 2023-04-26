<?php

namespace DemonicCM\DemonicDev\MobData\skincalc;



use pocketmine\entity\Entity;
use pocketmine\entity\Human;
use pocketmine\entity\Skin;
use pocketmine\math\Vector3;
use pocketmine\Player;

use DemonicCM\DemonicDev\Main;

class skincalc{
	
	public function skinCalculate($entityName){
		$entitySkinPNG = $entityGeometryName = $entityGeometryJSONFile = $folder = $entityName;
		$skinID = $entityName;
		/** Make Skin Data. */
        $path = Main::getInstance()->getDataFolder(). $folder . "/" . $entitySkinPNG . ".png";
        $image = imagecreatefrompng($path);
        $skinData = "";
		$sizeY = (int)getimagesize($path)[1]; // Allows different sizes.
        $sizeX = (int)getimagesize($path)[0]; 
		for ($y = 0; $y < $sizeY; $y++) {
            for ($x = 0; $x < $sizeX; $x++) {
                // Convert Image Pixel to RGBA
                $colorAt = imagecolorat($image, $x, $y);
                $a = ((~((int)($colorAt >> 24))) << 1) & 0xff;
                $r = ($colorAt >> 16) & 0xff;
                $g = ($colorAt >> 8) & 0xff;
                $b = $colorAt & 0xff;
                // Create a Byte Array
                $skinData .= chr($r) . chr($g) . chr($b) . chr($a);
            }
        }
		imagedestroy($image);
		$skin = new Skin(
			$skinID,
			$skinData,
			"",
			"geometry." . $entityGeometryName,
			file_get_contents(Main::getInstance()->getDataFolder(). $folder . "/" . $entityGeometryJSONFile . ".geo.json")
        );
		return $skin;
	}
}


