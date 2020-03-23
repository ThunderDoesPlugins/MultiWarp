<?php
declare(strict_types=1);
/** Created By Thunder33345 **/

namespace Thunder33345\MultiWarp\Commands\SubCommands;

use CortexPE\Commando\args\BooleanArgument;
use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\constraint\InGameRequiredConstraint;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat as Format;
use Thunder33345\MultiWarp\Commands\Arguments\WarpGroupArgument;
use Thunder33345\MultiWarp\MultiWarp;
use Thunder33345\MultiWarp\WarpGroup;

class WarpAddCommand extends MultiWarpSubCommand
{
	protected function prepare():void
	{
		$this->addConstraint(new InGameRequiredConstraint($this));
		$this->registerArgument(0, new WarpGroupArgument($this->getMultiWarp(), 'name', false));
		$this->registerArgument(1, new IntegerArgument('weight', true));
		$this->registerArgument(2, new BooleanArgument('facing', true));
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args):void
	{
		if(!$sender instanceof Player) return;
		if(!isset($args['weight'])) $args['weight'] = 1;
		if(!isset($args['facing'])) $args['facing'] = false;
		$weight = (int)$args['weight'];
		$facing = (bool)$args['facing'];
		if($facing) $facingText = "yes";
		else $facingText = "no";
		$warpGroup = $args['name'];
		if(!$warpGroup instanceof WarpGroup) return;
		$this->getMultiWarp()->addWarp($warpGroup, $sender, $weight, $facing);
		$sender->sendMessage(MultiWarp::PREFIX_OK . 'Added New Warp Point to Warp Group ' . Format::GOLD . $warpGroup->getName() . Format::GRAY .
			"(" . Format::WHITE . count($warpGroup->getAllWarps()) . Format::GRAY . ")" .
			"{weight:" . Format::WHITE . $weight . Format::GRAY . ', facing:' . Format::WHITE . $facingText . Format::GRAY . '}' . Format::WHITE . '.');

	}
}