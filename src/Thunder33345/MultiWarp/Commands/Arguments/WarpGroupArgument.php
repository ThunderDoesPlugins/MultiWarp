<?php
declare(strict_types=1);
/** Created By Thunder33345 **/

namespace Thunder33345\MultiWarp\Commands\Arguments;

use CortexPE\Commando\args\BaseArgument;
use pocketmine\command\CommandSender;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use Thunder33345\MultiWarp\MultiWarp;
use Thunder33345\MultiWarp\WarpGroup;

class WarpGroupArgument extends BaseArgument
{
	private $multiWarp;

	public function __construct(MultiWarp $multiWarp, string $name, bool $optional)
	{
		$this->multiWarp = $multiWarp;
		parent::__construct($name, $optional);
	}

	public function getNetworkType():int
	{
		return AvailableCommandsPacket::ARG_TYPE_STRING;
	}

	public function canParse(string $testString, CommandSender $sender):bool
	{
		return ($this->parse($testString, $sender) instanceof WarpGroup);
	}

	public function parse(string $argument, CommandSender $sender):?WarpGroup
	{
		return $this->multiWarp->getWarpList()->get($argument);
	}

	public function getTypeName():string
	{
		return "warpgroup";
	}
}