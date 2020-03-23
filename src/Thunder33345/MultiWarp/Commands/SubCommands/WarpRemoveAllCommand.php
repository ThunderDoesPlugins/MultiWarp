<?php
declare(strict_types=1);
/** Created By Thunder33345 **/

namespace Thunder33345\MultiWarp\Commands\SubCommands;

use CortexPE\Commando\args\BooleanArgument;
use CortexPE\Commando\constraint\InGameRequiredConstraint;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat as Format;
use Thunder33345\MultiWarp\Commands\Arguments\WarpGroupArgument;
use Thunder33345\MultiWarp\MultiWarp;
use Thunder33345\MultiWarp\WarpGroup;

class WarpRemoveAllCommand extends MultiWarpSubCommand
{
	protected function prepare():void
	{
		$this->registerArgument(0, new WarpGroupArgument($this->getMultiWarp(), 'name', false));
		$this->registerArgument(1, new BooleanArgument('inactive', false));
		$this->registerArgument(2, new BooleanArgument('active', false));
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args):void
	{
		$warpGroup = $args['name'];
		if(!$warpGroup instanceof WarpGroup) return;
		if($args['inactive']){
			$count = count($warpGroup->getInactiveWarps());
			$warpGroup->removeAllInactiveWarp();
			$sender->sendMessage(MultiWarp::PREFIX_WARN . "Removed All Inactive Warps" . Format::GRAY . "($count)" . Format::WHITE .
				" For " . Format::GOLD . "{$warpGroup->getName()}" . Format::WHITE . ".");
		}
		if($args['active']){
			$count = count($warpGroup->getActiveWarps());
			$warpGroup->removeAllWarp();
			$sender->sendMessage(MultiWarp::PREFIX_WARN . "Removed All Active Warps" . Format::GRAY . "($count)" . Format::WHITE .
				" For " . Format::GOLD . "{$warpGroup->getName()}" . Format::WHITE . ".");
		}
		if(!$args['inactive'] AND !$args['active']){
			$sender->sendMessage(MultiWarp::PREFIX_INFO . "Removed Nothing From " . Format::GOLD . "{$warpGroup->getName()}" . Format::WHITE . ".");
		}
	}
}