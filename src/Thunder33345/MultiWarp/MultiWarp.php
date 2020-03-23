<?php
declare(strict_types=1);
/** Created By Thunder33345 **/

namespace Thunder33345\MultiWarp;

use CortexPE\Commando\PacketHooker;
use Kint;
use pocketmine\event\Listener;
use pocketmine\level\Level;
use pocketmine\level\Location;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat as Format;
use Thunder33345\MultiWarp\Commands\MultiWarpCommand;
use Thunder33345\MultiWarp\Commands\MultiWarpUseCommand;

class MultiWarp extends PluginBase implements Listener
{
	public const NAME = Format::DARK_GREEN . "Multi" . Format::GOLD . "Warp";
	public const PREFIX_INFO = Format::AQUA . "[" . self::NAME . Format::AQUA . "] " . Format::WHITE;
	public const PREFIX_OK = Format::GREEN . "[" . self::NAME . Format::GREEN . "] " . Format::WHITE;
	public const PREFIX_WARN = Format::GRAY . "(" . Format::YELLOW . Format::BOLD . "!" . Format::RESET . Format::GRAY . ")" .
	Format::YELLOW . "[" . self::NAME . Format::YELLOW . "] " . Format::WHITE;
	public const PREFIX_ERROR = Format::GRAY . "(" . Format::RED . Format::BOLD . "!" . Format::RESET . Format::GRAY . ")" .
	Format::RED . "[" . self::NAME . Format::RED . "] " . Format::WHITE;
	private $warpList;

	public function onEnable()
	{
		//$this->initDebugger();
		$this->saveDefaultConfig();
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->initWarps();

		if(!class_exists(PacketHooker::class)){
			$this->getLogger()->critical(MultiWarp::PREFIX_ERROR . "Missing Commando Virion, Please install CortexPE\Commando or download from poggit.");
			$this->getServer()->getPluginManager()->disablePlugin($this);
			return;
		}
		if(!PacketHooker::isRegistered()) PacketHooker::register($this);

		$multiWarpCmd = new MultiWarpCommand($this, 'multiwarp', "MultiWarp Main Command", ['mtp', 'mwp']);
		$multiWarpCmd->setPermission('multiwarp.command');
		$this->getServer()->getCommandMap()->register($this->getName(), $multiWarpCmd);

		$multiWarpUseCmd = new MultiWarpUseCommand($this, 'multiwarpuse', "MultiWarp System Use Command");
		$multiWarpUseCmd->setPermission('multiwarp.system');
		$this->getServer()->getCommandMap()->register($this->getName(), $multiWarpUseCmd);

		#probs gonna delete
		#$multiWarpAdvanceCmd = new MultiWarpAdvance($this, 'multiwarpadvance', "MultiWarp Advance Main Command", ['mtpa', 'mwpa']);
		#$multiWarpAdvanceCmd->setPermission('multiwarp.advance');
		#$this->getServer()->getCommandMap()->register($this->getName(), $multiWarpAdvanceCmd);
	}

	public function onDisable()
	{
		$this->saveWarp();
	}

	private function initWarps()
	{
		$list = $this->getConfig()->get('warp-list');

		$warpList = new WarpList();
		foreach($list as $warpGroupName => $warpGroupData){
			$warpGroup = new WarpGroup($warpGroupName);
			foreach($warpGroupData as $index => $warpPointData){
				//config checking
				$required = ['x', 'y', 'z', 'l'];
				$error = [];
				foreach($required as $test){
					if(isset($warpPointData[$test])) continue;
					$error[] = $test;
				}
				if(count($error) > 0){
					$this->getLogger()->error("Config Error: Ignored entry for {$warpGroupName}[$index], Missing required: " . implode(",", $error));
					continue;
				}

				$yaw = @$warpPointData['yaw'];
				if(is_numeric($yaw)) $yaw = (float)$yaw;
				else $yaw = null;

				$pitch = @$warpPointData['pitch'];
				if(is_numeric($pitch)) $pitch = (float)$pitch;
				else $pitch = null;

				$weight = @$warpPointData['weight'];
				if(is_int($weight)) $weight = (int)$weight;
				else $weight = 1;

				$vector = new Vector3((float)$warpPointData['x'], (float)$warpPointData['y'], (float)$warpPointData['z']);
				$warpPoint = new WarpPoint($vector, (string)$warpPointData['l'], $weight,
					$yaw, $pitch);
				$warpGroup->addWarp($warpPoint);
			}
			$warpList->add($warpGroup);
		}
		$this->warpList = $warpList;
	}

	public function saveWarp()
	{
		$warpList = $this->warpList;
		if(!$warpList instanceof WarpList) return;
		$save = [];
		foreach($warpList->getAll() as $warpGroup){
			if(!$warpGroup instanceof WarpGroup) continue;
			$groupArray = [];
			$warps = $warpGroup->getAllWarps();
			foreach($warps as $warp){
				if(!$warp instanceof WarpPoint) continue;
				$vec = $warp->getVector3();
				$pointArray = ['x' => $vec->x, 'y' => $vec->y, 'z' => $vec->z, 'l' => $warp->getWorld(),
					'weight' => $warp->getWeight(), 'yaw' => $warp->getYaw(), 'pitch' => $warp->getPitch()];
				$groupArray[] = $pointArray;
			}
			$save[$warpGroup->getName()] = $groupArray;
		}
		$this->getConfig()->set('warp-list', $save);
		$this->getConfig()->save();
	}

	/**
	 * @param WarpGroup $warpGroup
	 * @param Location $location
	 * @param int $weight
	 * @param bool $facing
	 *
	 * @internal May be changed or removed
	 */
	public function addWarp(WarpGroup $warpGroup, Location $location, int $weight = 0, bool $facing = false)
	{//todo move into cmd
		$yaw = ($facing ? $location->getYaw() : null);
		$pitch = ($facing ? $location->getPitch() : null);

		$warpPoint = new WarpPoint($location, $location->getLevel()->getFolderName(), $weight, $yaw, $pitch);
		$warpGroup->addWarp($warpPoint);
	}

	/**
	 * @param WarpGroup $warpGroup
	 * @param Player $player
	 *
	 * @return bool
	 * @internal May be changed or removed
	 */
	public function findAndUseWarp(WarpGroup $warpGroup, Player $player):bool
	{
		$warpPoint = $warpGroup->getWarp();
		if($warpPoint instanceof WarpPoint){
			if(!$this->useWarp($warpGroup, $warpPoint, $player)){
				$levelName = $warpPoint->getWorld();
				$this->getLogger()->error("Runtime Error: World $levelName(warp: {$warpGroup->getName()}) not found, warp temporally disabled.");
				$warpGroup->deactivateWarp($warpPoint);
				$this->findAndUseWarp($warpGroup, $player);
			}
			return true;
		} else {
			return false;
		}
	}

	/**
	 * @param WarpGroup $warpGroup
	 * @param WarpPoint $warpPoint
	 * @param Player $player
	 *
	 * @return bool
	 * @internal May be changed or removed
	 */
	public function useWarp(WarpGroup $warpGroup, WarpPoint $warpPoint, Player $player):bool
	{
		$level = $this->tryLoadWarpWorld($warpGroup, $warpPoint);
		if(!$level instanceof Level)
			return false;
		$location = new Location($warpPoint->getVector3()->x, $warpPoint->getVector3()->y, $warpPoint->getVector3()->z,
			$warpPoint->getYaw() ?? $player->getYaw(), $warpPoint->getPitch() ?? $player->getPitch(),
			$level);
		$player->teleport($location);
		return true;
	}

	/**
	 * @param WarpGroup $warpGroup
	 * @param WarpPoint $warpPoint
	 *
	 * @return Level|null
	 * @internal May be changed or removed
	 */
	public function tryLoadWarpWorld(WarpGroup $warpGroup, WarpPoint $warpPoint):?Level
	{
		$levelName = $warpPoint->getWorld();
		$level = $this->getServer()->getLevelByName($levelName);
		if(!$level instanceof Level){
			$this->getLogger()->debug("Level \"$levelName\"(warp: {$warpGroup->getName()}) Not found, Attempting to load...");
			$level = $this->getServer()->getLevelByName($levelName);
		}
		if($level instanceof Level)
			return $level;
		else {
			return null;
		}
	}

	public function getWarpList():WarpList{ return $this->warpList; }

	private function initDebugger()
	{
		$dir = $this->getServer()->getDataPath() . 'bin' . DIRECTORY_SEPARATOR . 'kint.phar';
		require_once $dir;
		Kint\Renderer\CliRenderer::$force_utf8 = true;
		Kint::$app_root_dirs = [
			'/' . (string)str_replace('\\', '/', $this->getServer()->getDataPath()) => '<ROOT>',
			'/' . (string)str_replace('\\', '/', $this->getServer()->getPluginPath()) => '<PLUGIN>',
		];
		Kint::dump(Kint::$app_root_dirs);
	}
}