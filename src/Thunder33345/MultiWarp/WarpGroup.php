<?php
declare(strict_types=1);
/** Created By Thunder33345 **/

namespace Thunder33345\MultiWarp;

class WarpGroup
{
	protected $name = '';
	protected $total = 0;
	/** @var WarpPoint[] */
	protected $warps = [];
	/** @var WarpPoint[] */
	protected $inactiveWarps = [];

	public function __construct(string $name)
	{
		$this->name = $name;
	}

	public function addWarp(WarpPoint $warpPoint)
	{
		$total = $this->total + $warpPoint->getWeight();
		$this->warps[$total] = $warpPoint;
		$this->total = $total;
	}

	public function removeWarp(WarpPoint $warpNeedle)
	{
		foreach($this->warps as $num => $warp){
			if($warp !== $warpNeedle) continue;
			unset($this->warps[$num]);
		}
		$this->recompileWeights();
	}

	public function removeAllWarp()
	{
		$this->warps = [];
		$this->recompileWeights();
	}

	public function getWarp():?WarpPoint
	{
		$rand = $this->getRandom($this->total);
		foreach($this->warps as $num => $warp){
			if($rand <= $num){
				return $warp;
			}
		}
		$this->recompileWeights();//assume smth fucked up
		return null;
	}

	public function addInactiveWarp(WarpPoint $warpPoint)
	{
		$this->inactiveWarps[] = $warpPoint;
	}

	public function deactivateWarp(WarpPoint $warpNeedle)
	{
		foreach($this->warps as $num => $warp){
			if($warp !== $warpNeedle) continue;
			unset($this->warps[$num]);
			$this->inactiveWarps[] = $warp;
		}
		$this->recompileWeights();
	}

	public function removeInactiveWarp(WarpPoint $warpNeedle)
	{
		foreach($this->inactiveWarps as $num => $warp){
			if($warp !== $warpNeedle) continue;
			unset($this->inactiveWarps[$num]);
		}

	}

	public function removeAllInactiveWarp()
	{
		$this->inactiveWarps = [];
	}

	protected function recompileWeights()
	{
		$warps = $this->warps;
		$this->warps = [];
		$this->total = 0;
		foreach($warps as $warp){
			$this->addWarp($warp);
		}
	}

	protected function getRandom(int $max)
	{
		return mt_rand(0, $max);
	}

	public function getName(){ return $this->name; }

	public function getAllWarps():array
	{
		return array_merge(array_values($this->warps), $this->inactiveWarps);
	}

	/* @return WarpPoint[] */
	public function getActiveWarps():array{ return $this->warps; }

	/* @return WarpPoint[] */
	public function getInactiveWarps():array{ return $this->inactiveWarps; }
}