<?php
declare(strict_types=1);
/** Created By Thunder33345 **/

namespace Thunder33345\MultiWarp\Commands\SubCommands;

use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\constraint\InGameRequiredConstraint;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat as Format;
use Thunder33345\MultiWarp\Commands\Arguments\WarpGroupArgument;
use Thunder33345\MultiWarp\MultiWarp;
use Thunder33345\MultiWarp\WarpGroup;
use Thunder33345\MultiWarp\WarpPoint;

class WarpRemoveNearCommand extends MultiWarpSubCommand
{
	protected function prepare():void
	{
		$this->addConstraint(new InGameRequiredConstraint($this));
		$this->registerArgument(0, new WarpGroupArgument($this->getMultiWarp(), 'name', false));
		$this->registerArgument(1, new IntegerArgument('distance', true));
		$this->registerArgument(2, new IntegerArgument('count', true));
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args):void
	{
		if(!$sender instanceof Player) return;
		$warpGroup = $args['name'];
		if(!$warpGroup instanceof WarpGroup) return;
		if(!isset($args['distance']) OR !is_numeric($args['distance'])) $args['distance'] = 5;
		if(!isset($args['count']) OR !is_numeric($args['count'])) $args['count'] = 1;

		$maxDistance = $args['distance'];
		$maxCount = $args['count'];

		$count = 0;
		$nearWarps = [];

		foreach($warpGroup->getActiveWarps() as $warp){
			if(!$warp instanceof WarpPoint) continue;
			if($warp->getWorld() !== $sender->getLevel()->getFolderName()) continue;
			$distance = $warp->getVector3()->distance($sender);
			if($distance > $maxDistance) continue;
			$nearWarps[] = $warp;
		}
		$center = $sender->asVector3();
		usort($nearWarps, function ($a, $b) use ($center){
			/** @var $a WarpPoint */
			/** @var $b WarpPoint */
			$aDist = $a->getVector3()->distance($center);
			$bDist = $b->getVector3()->distance($center);
			if($aDist === $bDist){
				return 0;
			}
			return ($aDist < $bDist) ? -1 : 1;
		});

		foreach($nearWarps as $warp){
			if($count >= $maxCount) break;
			$warpGroup->removeWarp($warp);
			$count++;
		}

		$sender->sendMessage(MultiWarp::PREFIX_WARN . "Removed " . Format::AQUA . $count . Format::WHITE . " warps within " . Format::AQUA . $maxDistance . Format::WHITE . " blocks.");
	}
}