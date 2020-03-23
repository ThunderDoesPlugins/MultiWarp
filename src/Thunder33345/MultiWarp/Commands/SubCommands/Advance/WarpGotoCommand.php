<?php
declare(strict_types=1);
/** Created By Thunder33345 **/

namespace Thunder33345\MultiWarp\Commands\SubCommands\Advance;

use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\constraint\InGameRequiredConstraint;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use Thunder33345\MultiWarp\Commands\Arguments\WarpGroupArgument;
use Thunder33345\MultiWarp\Commands\SubCommands\MultiWarpSubCommand;
use Thunder33345\MultiWarp\WarpGroup;

class WarpGotoCommand extends MultiWarpSubCommand
{
	protected function prepare():void
	{
		$this->addConstraint(new InGameRequiredConstraint($this));
		$this->registerArgument(0, new WarpGroupArgument($this->getMultiWarp(), 'warpgroup', false));
		$this->registerArgument(1, new IntegerArgument('index', false));
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args):void
	{
		$warpGroup = $args['warpgroup'];
		$index = $args['index'];
		if(!$warpGroup instanceof WarpGroup) return;
		/** @var $sender Player */
		if(!$sender instanceof Player) return;
		$activeWarp = $warpGroup->getActiveWarps();
		$i = 0;
		foreach($activeWarp as $warpPoint){
			if($i !== $index){
				$i++;
				continue;
			}
			$status = $this->getMultiWarp()->useWarp($warpGroup, $warpPoint, $sender);
			//todo error/success msg
			return;
		}
	}

}