<?php
declare(strict_types=1);
/** Created By Thunder33345 **/

namespace Thunder33345\MultiWarp;


use pocketmine\math\Vector3;

class WarpPoint
{
	protected $vector3;
	protected $world;
	protected $weight;
	protected $yaw, $pitch;

	public function __construct(Vector3 $vector3, string $world, int $weight = 0, ?float $yaw = null, ?float $pitch = null)
	{
		$this->vector3 = $vector3->asVector3();
		$this->world = $world;
		$this->weight = $weight;
		$this->yaw = $yaw;
		$this->pitch = $pitch;
	}


	public function getVector3():Vector3{ return $this->vector3; }

	public function getWorld():string{ return $this->world; }

	public function getWeight():int{ return $this->weight; }

	public function getYaw():?float{ return $this->yaw; }

	public function getPitch():?float{ return $this->pitch; }

}