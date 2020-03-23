<?php
declare(strict_types=1);
/** Created By Thunder33345 **/

namespace Thunder33345\MultiWarp\Commands\SubCommands\Advance;

use pocketmine\command\CommandSender;
use Thunder33345\MultiWarp\Commands\Arguments\WarpGroupArgument;
use Thunder33345\MultiWarp\Commands\SubCommands\MultiWarpSubCommand;
use Thunder33345\MultiWarp\WarpGroup;

class WarpListIndexCommand extends MultiWarpSubCommand
{
	protected function prepare():void
	{
		$this->registerArgument(0, new WarpGroupArgument($this->getMultiWarp(), 'warpgroup', false));
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args):void
	{
		$warpGroup = $args['warpgroup'];
		if(!$warpGroup instanceof WarpGroup) return;
		$activeWarp = $warpGroup->getActiveWarps();
		$sender->sendMessage("Listing warps of {$warpGroup->getName()}");
		$sender->sendMessage("Listing active warps:");
		$i = 0;
		foreach($activeWarp as $warpPoint){
			$v3 = $warpPoint->getVector3();
			$sender->sendMessage("#$i {$v3->x}:{$v3->y}:{$v3->z} lvl:{$warpPoint->getWorld()} weight:{{$warpPoint->getWeight()}");
			$i++;
		}
		$sender->sendMessage("Listing inactive warps:");
		$inactiveWarp = $warpGroup->getInactiveWarps();
		$i = 0;
		foreach($inactiveWarp as $warpPoint){
			$v3 = $warpPoint->getVector3();
			$sender->sendMessage("#$i {$v3->x}:{$v3->y}:{$v3->z} lvl:{$warpPoint->getWorld()} weight:{{$warpPoint->getWeight()}");
			$i++;
		}
	}
}