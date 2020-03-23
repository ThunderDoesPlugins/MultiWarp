<?php
declare(strict_types=1);
/** Created By Thunder33345 **/

namespace Thunder33345\MultiWarp\Commands\SubCommands;

use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat as Format;
use Thunder33345\MultiWarp\MultiWarp;

class WarpListCommand extends MultiWarpSubCommand
{
	protected function prepare():void
	{

	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args):void
	{
		$warpGroups = $this->getMultiWarp()->getWarpList()->getAll();
		$sender->sendMessage(MultiWarp::PREFIX_INFO . "Warp Group List:");
		if(count($warpGroups) == 0){
			$sender->sendMessage(MultiWarp::PREFIX_OK . "No Warps Created Yet!");
		}
		foreach($warpGroups as $warpGroup){
			$active = count($warpGroup->getActiveWarps());
			$inactive = count($warpGroup->getInactiveWarps());
			$total = count($warpGroup->getAllWarps());
			if($total === $active){
				$sender->sendMessage(Format::WHITE . "# " . Format::GOLD . $warpGroup->getName() . Format::WHITE . "(" . Format::GREEN . $active . Format::WHITE . ")");
			} else {
				$sender->sendMessage(Format::WHITE . "# " . Format::GOLD . $warpGroup->getName() .
					Format::WHITE . "{" . Format::GREEN . $active . Format::WHITE . '/' . Format::RED . $inactive . Format::WHITE . '} ('
					. Format::GRAY . $total . Format::WHITE . ')');
			}
		}
	}
}