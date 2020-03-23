<?php
declare(strict_types=1);
/** Created By Thunder33345 **/

namespace Thunder33345\MultiWarp\Commands\Arguments;

use CortexPE\Commando\args\BaseArgument;
use pocketmine\command\CommandSender;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\Player;

class PlayerArgument extends BaseArgument
{
	public function __construct(string $name, bool $optional)
	{
		parent::__construct($name, $optional);
	}

	public function getNetworkType():int
	{
		return AvailableCommandsPacket::ARG_TYPE_TARGET;
	}

	public function canParse(string $testString, CommandSender $sender):bool
	{
		return ($this->parse($testString, $sender) instanceof Player);
	}

	public function parse(string $argument, CommandSender $sender):?Player
	{
		return $sender->getServer()->getPlayer($argument);
	}

	public function getTypeName():string
	{
		return "player";
	}
}