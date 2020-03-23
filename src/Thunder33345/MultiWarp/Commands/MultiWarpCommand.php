<?php
declare(strict_types=1);
/** Created By Thunder33345 **/

namespace Thunder33345\MultiWarp\Commands;

use CortexPE\Commando\args\TextArgument;
use CortexPE\Commando\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat as Format;
use Thunder33345\MultiWarp\Commands\SubCommands\WarpAddCommand;
use Thunder33345\MultiWarp\Commands\SubCommands\WarpCreateCommand;
use Thunder33345\MultiWarp\Commands\SubCommands\WarpDeleteCommand;
use Thunder33345\MultiWarp\Commands\SubCommands\WarpHelpCommand;
use Thunder33345\MultiWarp\Commands\SubCommands\WarpListCommand;
use Thunder33345\MultiWarp\Commands\SubCommands\WarpRemoveAllCommand;
use Thunder33345\MultiWarp\Commands\SubCommands\WarpRemoveNearCommand;
use Thunder33345\MultiWarp\Commands\SubCommands\WarpUseCommand;
use Thunder33345\MultiWarp\MultiWarp;

class MultiWarpCommand extends BaseCommand
{
	private $multiWarp;
	/** @var $helpCommand WarpHelpCommand */
	private $helpCommand;

	public function __construct(MultiWarp $multiWarp, string $name, string $description = "", array $aliases = [])
	{
		$this->multiWarp = $multiWarp;
		parent::__construct($name, $description, $aliases);
	}

	/*
	 * todo planning subcmds and cmds
	 * user facing cmd:
	 * multiwarp
	 * subcmds~~
	 * use: warpgroup name
	 * add: warpgroup name, weight, facing
	 * list: null
	 * removenear: warpgroup name, distance, count
	 * -|advanced|-
	 * listindex: warpgroupname (list warp point's index and status)
	 * gotoindex: warpgroupname, int (goto warp index)
	 * manualadd: warpgroup name, vec3, world, weight, yaw, pitch
	 * remove: warpgroup name, index
	 * removeall: warpgroup name
	 * removeallinactive: warpgroup name
	 *
	 * automatedcmd:
	 * multiwarpuse warpgroup name, target name
	 */

	protected function prepare():void
	{
		//$this->registerArgument(0, new TextArgument('str', true));//use to allow any fallback

		$root = 'multiwarp.command';
		$this->registerSubCommand(new WarpUseCommand($this->multiWarp, "use", "Use a Warp", "$root.use", ["u"]));
		$this->registerSubCommand(new WarpAddCommand($this->multiWarp, "add", "Add Warp at your current location", "$root.add", ["a",'s','set']));
		$this->registerSubCommand(new WarpCreateCommand($this->multiWarp, "create", "Create a new Warp group", "$root.create", ["c",'n','new']));
		$this->registerSubCommand(new WarpListCommand($this->multiWarp, "list", "List Warp groups", "$root.list", ["l",'ls','all']));

		$this->registerSubCommand(new WarpRemoveNearCommand($this->multiWarp, "removenear", "Remove Warp near you", "$root.removenear", ["r"]));
		$this->registerSubCommand(new WarpRemoveAllCommand($this->multiWarp, "removeall", "Remove all Warps in a group", "$root.removeall"));
		$this->registerSubCommand(new WarpDeleteCommand($this->multiWarp, "delete", "Delete a Warp group", "$root.delete"));

		$helpCommand = new WarpHelpCommand($this->multiWarp, $this, "help", "Show help info", "$root.help", ['h']);
		$this->helpCommand = $helpCommand;
		$this->registerSubCommand($helpCommand);
	}

	public function sendError(int $errorCode, array $args = []):void
	{
		$str = $this->errorMessages[$errorCode];
		foreach($args as $item => $value){
			$str = str_replace("{{$item}}", Format::AQUA . $value . Format::RED, $str);
		}
		if(is_string($str))
			$this->currentSender->sendMessage(MultiWarp::PREFIX_ERROR . $str);
		else
			$this->currentSender->sendMessage($str);
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args):void
	{
		$this->helpCommand->onRun($sender, "", []);
	}
}


