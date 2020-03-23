<?php
declare(strict_types=1);
/** Created By Thunder33345 **/

namespace Thunder33345\MultiWarp\Commands\SubCommands;

use CortexPE\Commando\constraint\InGameRequiredConstraint;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat as Format;
use Thunder33345\MultiWarp\Commands\Arguments\WarpGroupArgument;
use Thunder33345\MultiWarp\MultiWarp;
use Thunder33345\MultiWarp\WarpGroup;

class WarpUseCommand extends MultiWarpSubCommand
{
	public function prepare():void
	{
		$this->addConstraint(new InGameRequiredConstraint($this));
		$this->registerArgument(0, new WarpGroupArgument($this->getMultiWarp(), 'name', false));
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args):void
	{
		if(!$sender instanceof Player) return;
		$warpGroup = $args['name'];
		if(!$warpGroup instanceof WarpGroup) return;
		$result = $this->getMultiWarp()->findAndUseWarp($warpGroup, $sender);//todo per warp permission based?
		if($result){
			$sender->sendMessage(MultiWarp::PREFIX_OK . "Warped you to " . Format::GOLD . $warpGroup->getName() . Format::WHITE . '.');
		} else {
			$sender->sendMessage(MultiWarp::PREFIX_ERROR . "Failed to warp you to " . Format::GOLD . $warpGroup->getName() . Format::WHITE . '.');
		}
	}
}