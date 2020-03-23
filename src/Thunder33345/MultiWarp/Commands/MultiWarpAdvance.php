<?php
declare(strict_types=1);
/** Created By Thunder33345 **/

namespace Thunder33345\MultiWarp\Commands;

use CortexPE\Commando\BaseCommand;
use pocketmine\command\CommandSender;
use Thunder33345\MultiWarp\Commands\SubCommands\Advance\WarpGotoCommand;
use Thunder33345\MultiWarp\Commands\SubCommands\Advance\WarpListIndexCommand;
use Thunder33345\MultiWarp\MultiWarp;

class MultiWarpAdvance extends BaseCommand
{
	private $multiWarp;

	public function __construct(MultiWarp $multiWarp, string $name, string $description = "", array $aliases = [])
	{
		$this->multiWarp = $multiWarp;
		parent::__construct($name, $description, $aliases);
	}

	/*
	 * -|advanced|-
	 * listindex: warpgroupname (list warp point's index and status)
	 * gotoindex: warpgroupname, int (goto warp index)
	 * manualadd: warpgroup name, vec3, world, weight, yaw, pitch
    * remove: warpgroup name, index
	 * removeinactive: warpgroup name, index
	 * removeall: warpgroup name
	 * removeallinactive: warpgroup name
	 *
	 * Priority:
	 * removeall, removeallinactive
	 */
	protected function prepare():void
	{
		$root = 'multiwarp.advance';
		$this->registerSubCommand(new WarpListIndexCommand($this->multiWarp, "list", "List Warp Indexes", "$root.list", ["l"]));
		$this->registerSubCommand(new WarpGotoCommand($this->multiWarp, "goto", "Goto Warp Index", "$root.goto", ["g"]));

	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args):void
	{

	}
}