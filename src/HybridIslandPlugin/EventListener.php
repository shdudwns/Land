<?php

namespace HybridIslandPlugin\listener;

use pocketmine\event\Listener;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\player\Player;
use HybridIslandPlugin\world\IslandManager;
use HybridIslandPlugin\world\GridLandManager;
use HybridIslandPlugin\world\SkyBlockManager;
use HybridIslandPlugin\Main;

class EventListener implements Listener {

    public function __construct() {
        Main::getInstance()->getServer()->getPluginManager()->registerEvents($this, Main::getInstance());
    }

    // ✅ 블록 설치 및 파괴 보호
    public function onBlockPlace(BlockPlaceEvent $event): void {
        $player = $event->getPlayer();
        $pos = $event->getBlock()->getPosition();
        
        if (!$this->isOwnerOrMember($player, $pos)) {
            $player->sendMessage("§c해당 지역에 블록을 설치할 수 없습니다.");
            $event->cancel();
        }
    }

    public function onBlockBreak(BlockBreakEvent $event): void {
        $player = $event->getPlayer();
        $pos = $event->getBlock()->getPosition();

        if (!$this->isOwnerOrMember($player, $pos)) {
            $player->sendMessage("§c해당 지역의 블록을 파괴할 수 없습니다.");
            $event->cancel();
        }
    }

    // ✅ PvP 보호
    public function onPvP(EntityDamageByEntityEvent $event): void {
        $damager = $event->getDamager();
        $entity = $event->getEntity();

        if ($damager instanceof Player && $entity instanceof Player) {
            if (!$this->isOwnerOrMember($damager, $entity->getPosition())) {
                $damager->sendMessage("§c이 지역에서는 PvP가 허용되지 않습니다.");
                $event->cancel();
            }
        }
    }

    // ✅ 접근 권한 관리
    public function onPlayerMove(PlayerMoveEvent $event): void {
        $player = $event->getPlayer();
        $pos = $player->getPosition();

        if (!$this->isOwnerOrMember($player, $pos)) {
            $player->sendMessage("§c이 지역에 들어갈 수 없습니다.");
            $event->cancel();
        }
    }

    // ✅ 소유자 또는 멤버 확인 (모든 섬 타입 연동)
    private function isOwnerOrMember(Player $player, Vector3 $pos): bool {
        // Island
        if (IslandManager::hasIsland($player)) {
            $island = IslandManager::getIslandByPosition($pos);
            if ($island !== null && ($island["owner"] === $player->getName() || in_array($player->getName(), $island["members"] ?? []))) {
                return true;
            }
        }

        // GridLand
        if (GridLandManager::hasGridLand($player)) {
            $gridLand = GridLandManager::getGridLandByPosition($pos);
            if ($gridLand !== null && ($gridLand["owner"] === $player->getName() || in_array($player->getName(), $gridLand["members"] ?? []))) {
                return true;
            }
        }

        // SkyBlock
        if (SkyBlockManager::hasSkyBlock($player)) {
            $skyBlock = SkyBlockManager::getSkyBlockByPosition($pos);
            if ($skyBlock !== null && ($skyBlock["owner"] === $player->getName() || in_array($player->getName(), $skyBlock["members"] ?? []))) {
                return true;
            }
        }

        return false;
    }
}
