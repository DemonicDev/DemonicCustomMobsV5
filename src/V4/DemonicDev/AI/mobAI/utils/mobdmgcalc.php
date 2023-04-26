<?php

namespace DemonicCM\DemonicDev\AI\mobAI\utils;

//*Took from revivalpmmp\PureEntities*\\
//MOBDAMAGECALCULATOR\\
use LogLevel;
use pocketmine\entity\Entity;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\Item;
use pocketmine\item\ItemIds;
use pocketmine\Player;
use DemonicCM\DemonicDev\Main;

/**
 * Class MobDamageCalculator
 *
 * A simple class that calculates the damage done to a player by respecting the armor the
 * player is wearing (and later also enchantments). From the wiki:
 *
 * Defense points are each signified by half of a shirt of mail in the armor bar above the health bar.
 * Each defense point will reduce any damage dealt to the player which is absorbed by armor by 4%, increasing
 * additively with the number of defense points. Different materials and combinations of armor provide different
 * levels of defense.
 *
 * @package revivalpmmp\pureentities\utils
 */
class mobdmgcalc{

	/**
	 * Damage reduction by armor item in percent (while 1 armor point takes 4% damage which
	 * means when wearing full diamond armor - the damage is reduced by 80%)
	 *
	 * @var array
	 */
	private static $REDUCTION_DEFINITIONS = array(
		# no armor
		ItemIds::AIR => 0, # no armor points
		# helmets
		ItemIds::LEATHER_CAP => 4, # 1 armor point
		ItemIds::GOLD_HELMET => 8, # 2 armor points
		ItemIds::IRON_HELMET => 8, # 2 armor points
		ItemIds::DIAMOND_HELMET => 12, # 3 armor points
		# leggings
		ItemIds::LEATHER_PANTS => 8, # 2 armor points
		ItemIds::GOLD_LEGGINGS => 12, # 3 armor points
		ItemIds::IRON_LEGGINGS => 20, # 5 armor points
		ItemIds::DIAMOND_LEGGINGS => 24, # 6 armor points
		# boots
		ItemIds::LEATHER_BOOTS => 4, # 1 armor point
		ItemIds::GOLD_BOOTS => 4, # 1 armor point
		ItemIds::IRON_BOOTS => 8, # 2 armor points
		ItemIds::DIAMOND_BOOTS => 12, # 3 armor point
		# chestplate
		ItemIds::LEATHER_TUNIC => 12, # 3 armor points
		ItemIds::GOLD_CHESTPLATE => 20, # 5 armor points
		ItemIds::IRON_CHESTPLATE => 24, # 6 armor points
		ItemIds::DIAMOND_CHESTPLATE => 32 # 8 armor points
		
		//DEMONICDEV---> api to add custom armor :DDD
	);

	/**
	 * Returns the final damage for a specific player and the amount of base damage coming in
	 *
	 * @param Entity $player the player to be checked
	 * @param float  $damageFromEntity the final damage from the entity
	 * @return float the final damage calculated with respect to armor etc. pp worn by player
	 */
	public static function calculateFinalDamage(Entity $player, float $damageFromEntity) : float{
		if($player instanceof Player and !$player->isClosed()){
			$playerArmor = $player->getArmorInventory();
			$armorItems = [$playerArmor->getHelmet(), $playerArmor->getChestplate(), $playerArmor->getLeggings(), $playerArmor->getBoots()];

			if($armorItems !== null and sizeof($armorItems) > 0){
				// complete damage reduction in percent
				$reductionInPercent = 0;
				$enchantEpf = 0; // for items enchanted max capped to 20 (EPF = Enchantment Protection Factor)

				// check each worn armor
				foreach($armorItems as $armorItem){
					/**
					 * @var $armorItem Item
					 */
					if(array_key_exists($armorItem->getId(), self::$REDUCTION_DEFINITIONS)){ // TODO: there are some armor items out which are not defined here
						$reduction = self::$REDUCTION_DEFINITIONS[$armorItem->getId()];
						if($reduction !== null and $reduction > 0){
							$reductionInPercent += $reduction;
						}

						foreach($armorItem->getEnchantments() as $enchantment){
							if($enchantment->getId() === Enchantment::PROTECTION){
								$enchantEpf += $enchantment->getLevel(); // see http://minecraft.gamepedia.com/Armor#Enchantments
							}
						}
					}else{
						//Main::logOutput("MobDamageCalculator: undefined armor item set: $armorItem", LogLevel::WARNING);
					}
				}

				$totalDamage = $damageFromEntity;
				// reduce damage by x percent - depending on which armor is worn by player
				if($reductionInPercent > 0){
				//	Main::logOutput("MobDamageCalculator: damage of entity reduced by $reductionInPercent by armor worn");
					$totalDamage = $totalDamage - ($totalDamage * $reductionInPercent / 100);
				}
				// now check enchantments
				if($enchantEpf > 0){
					if($enchantEpf > 20){
						$enchantEpf = 20;
					}
				//	Main::logOutput("MobDamageCalculator: damage of entity will be reduced by $enchantEpf EPF points.");
					$totalDamage = $totalDamage * (1 - $enchantEpf / 25);
				}
			//	Main::logOutput("MobDamageCalculator: full calculated damage: $totalDamage, original damage: $damageFromEntity");
				return $totalDamage;
			}
		}

		return $damageFromEntity;
	}

}