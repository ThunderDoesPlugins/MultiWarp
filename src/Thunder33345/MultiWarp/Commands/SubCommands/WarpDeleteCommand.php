<?php
declare(strict_types=1);
/** Created By Thunder33345 **/

namespace Thunder33345\MultiWarp\Commands\SubCommands;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat as Format;
use Thunder33345\MultiWarp\Commands\Arguments\WarpGroupArgument;
use Thunder33345\MultiWarp\MultiWarp;
use Thunder33345\MultiWarp\WarpGroup;

class WarpDeleteCommand extends MultiWarpSubCommand
{
	protected function prepare():void
	{
		$this->registerArgument(0, new WarpGroupArgument($this->getMultiWarp(), 'name', false));
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args):void
	{
		$warpGroup = $args['name'];
		if(!$warpGroup instanceof WarpGroup) return;
		$this->getMultiWarp()->getWarpList()->remove($warpGroup);
		$sender->sendMessage(MultiWarp::PREFIX_WARN . 'Deleted Warp Group ' . Format::GOLD . $warpGroup->getName()
			. Format::GRAY . '(' . Format::WHITE . count($warpGroup->getAllWarps()) . Format::GRAY . ')' . Format::WHITE . '.');
	}
}