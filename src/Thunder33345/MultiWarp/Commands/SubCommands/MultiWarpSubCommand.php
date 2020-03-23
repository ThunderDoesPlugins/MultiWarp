<?php
declare(strict_types=1);
/** Created By Thunder33345 **/

namespace Thunder33345\MultiWarp\Commands\SubCommands;

use CortexPE\Commando\BaseSubCommand;
use Thunder33345\MultiWarp\MultiWarp;

abstract class MultiWarpSubCommand extends BaseSubCommand
{
	private $multiWarp;

	public function __construct(MultiWarp $multiWarp, string $name, string $description = "", string $permission = "", array $aliases = [])
	{
		$this->multiWarp = $multiWarp;
		$this->setPermission($permission);
		parent::__construct($name, $description, $aliases);
	}

	protected function getMultiWarp():MultiWarp{ return $this->multiWarp; }
}