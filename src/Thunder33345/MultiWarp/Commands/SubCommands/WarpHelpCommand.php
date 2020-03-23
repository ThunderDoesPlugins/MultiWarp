<?php
declare(strict_types=1);
/** Created By Thunder33345 **/

namespace Thunder33345\MultiWarp\Commands\SubCommands;

use CortexPE\Commando\BaseSubCommand;
use pocketmine\command\CommandSender;
use pocketmine\form\Form;
use pocketmine\utils\TextFormat as Format;
use Thunder33345\MultiWarp\Commands\MultiWarpCommand;
use Thunder33345\MultiWarp\MultiWarp;

class WarpHelpCommand extends MultiWarpSubCommand
{
	private $command;

	public function __construct(MultiWarp $multiWarp, MultiWarpCommand $multiWarpCommand, string $name, string $description = "", string $permission = "", array $aliases = [])
	{
		$this->command = $multiWarpCommand;
		parent::__construct($multiWarp, $name, $description, $permission, $aliases);
	}

	protected function prepare():void
	{

	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args):void
	{
		$mainCommand = $this->command;
		$sender->sendMessage(MultiWarp::PREFIX_INFO . MultiWarp::NAME . Format::WHITE.' v' . Format::GOLD . $this->getMultiWarp()->getDescription()->getVersion()
			. Format::WHITE . ' By ' . Format::GREEN . 'Thunder33345');
		$sender->sendMessage(MultiWarp::NAME . Format::WHITE . ' Aliases: ' . implode(',', $mainCommand->getAliases()));
		$sender->sendMessage(MultiWarp::NAME . Format::WHITE . ' Commands list:');
		foreach($mainCommand->getSubCommands() as $label => $subCommand){
			/** @var $subCommand BaseSubCommand */
			if($label !== $subCommand->getName()) continue;//ignore aliases

			$header = Format::GRAY . "#" . Format::GREEN . $subCommand->getName();
			$aliases = $subCommand->getAliases();
			if(count($aliases) > 0){
				$header .= Format::GRAY . ' (' . Format::WHITE . implode(Format::GRAY . ',' . Format::WHITE, $aliases) . Format::GRAY . ')';
			}

			$sender->sendMessage($header);
			$sender->sendMessage(Format::GOLD . "- " . Format::WHITE . $subCommand->getDescription());
		}
	}
}