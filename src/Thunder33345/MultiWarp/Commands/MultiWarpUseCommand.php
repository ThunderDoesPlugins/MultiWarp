<?php
declare(strict_types=1);
/** Created By Thunder33345 **/

namespace Thunder33345\MultiWarp\Commands;

use CortexPE\Commando\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat as Format;
use Thunder33345\MultiWarp\Commands\Arguments\PlayerArgument;
use Thunder33345\MultiWarp\Commands\Arguments\WarpGroupArgument;
use Thunder33345\MultiWarp\MultiWarp;
use Thunder33345\MultiWarp\WarpGroup;

class MultiWarpUseCommand extends BaseCommand
{
	private $multiWarp;

	public function __construct(MultiWarp $multiWarp, string $name, string $description = "", array $aliases = [])
	{
		$this->multiWarp = $multiWarp;
		parent::__construct($name, $description, $aliases);
	}

	protected function prepare():void
	{
		$this->registerArgument(0, new WarpGroupArgument($this->multiWarp, 'name', false));
		$this->registerArgument(1, new PlayerArgument('player', false));
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args):void
	{
		$warp = $args['name'];
		if(!$warp instanceof WarpGroup) return;
		$player = $args['player'];
		if(!$player instanceof Player) return;

		$status = $this->multiWarp->findAndUseWarp($warp, $player);
		if(!$status){
			$player->sendMessage(MultiWarp::PREFIX_ERROR . 'ERROR: Failed to warp you.');
			$this->multiWarp->getLogger()->error(MultiWarp::PREFIX_ERROR . "Failed to warp "
				. $player->getName() . " to " . Format::GOLD . $warp->getName() . Format::WHITE . '.');
		}
	}
}
