<?php
declare(strict_types=1);
/** Created By Thunder33345 **/

namespace Thunder33345\MultiWarp\Commands\SubCommands;

use CortexPE\Commando\args\TextArgument;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat as Format;
use Thunder33345\MultiWarp\MultiWarp;
use Thunder33345\MultiWarp\WarpGroup;

class WarpCreateCommand extends MultiWarpSubCommand
{
	protected function prepare():void
	{
		$this->registerArgument(0, new TextArgument('name', false));
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args):void
	{
		$warpGroupName = $args['name'];
		$all = $this->getMultiWarp()->getWarpList()->getAll();
		if(isset($all[$warpGroupName])){
			$warpGroup = $all[$warpGroupName];
			if($warpGroup instanceof WarpGroup){
				$sender->sendMessage(MultiWarp::PREFIX_ERROR . 'Warp Group ' . Format::GOLD . $warpGroup->getName(). Format::WHITE . ' already exist.');
				return;
			}
		}

		$new = new WarpGroup($warpGroupName);
		$this->getMultiWarp()->getWarpList()->add($new);
		$sender->sendMessage(MultiWarp::PREFIX_INFO . 'Created new Warp Group ' . Format::GOLD . $new->getName() . Format::WHITE . '.');
	}
}