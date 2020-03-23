<?php
declare(strict_types=1);
/** Created By Thunder33345 **/

namespace Thunder33345\MultiWarp;

class WarpList
{
	/** @var WarpGroup[] */
	private $groups = [];

	public function add(WarpGroup $warpGroup, bool $overwrite = false)
	{
		if(!isset($this->groups[$warpGroup->getName()]) OR $overwrite)
			$this->groups[$warpGroup->getName()] = $warpGroup;
	}

	public function remove(WarpGroup $warpGroup)
	{
		if($this->groups[$warpGroup->getName()] === $warpGroup)
			$this->removeByName($warpGroup->getName());
	}

	public function removeByName(string $warpGroupName)
	{
		unset($this->groups[$warpGroupName]);
	}

	public function get(string $name):?WarpGroup
	{
		return $this->groups[$name] ?? null;
	}

	/** @return  WarpGroup[] */
	public function getAll():array{ return $this->groups; }
}