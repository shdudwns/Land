<?php

namespace HybridIslandPlugin\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use HybridIslandPlugin\world\SkyBlockManager;
use HybridIslandPlugin\Main;
use HybridIslandPlugin\command\utils\SubCommandMap;

class SkyBlockCommand extends Command {

    private SubCommandMap $subCommandMap;

    public function __construct() {
        parent::__construct("skyblock", "SkyBlock 관리 명령어", "/skyblock <create|delete|home|info>", ["sb"]);
        $this->setPermission("hybridislandplugin.command.skyblock");

        // ✅ SubCommandMap 연동
        $this->subCommandMap = new SubCommandMap();
        $this->subCommandMap->registerSubCommand("create", function(Player $sender) {
            SkyBlockManager::createSkyBlock($sender);
        });
        $this->subCommandMap->registerSubCommand("delete", function(Player $sender) {
            SkyBlockManager::deleteSkyBlock($sender);
        });
        $this->subCommandMap->registerSubCommand("home", function(Player $sender) {
            SkyBlockManager::teleportToSkyBlock($sender);
        });
        $this->subCommandMap->registerSubCommand("info", function(Player $sender) {
            $info = SkyBlockManager::getSkyBlockInfo($sender);
            $sender->sendMessage($info);
        });
    }

    public function execute(CommandSender $sender, string $label, array $args): bool {
        if (!$sender instanceof Player) {
            $sender->sendMessage("§c플레이어만 사용 가능합니다.");
            return false;
        }

        if (empty($args[0])) {
            $sender->sendMessage("§a사용 가능한 명령어:");
            foreach ($this->subCommandMap->getAll() as $subCommand => $callback) {
                $sender->sendMessage("§e/skyblock $subCommand");
            }
            return false;
        }

        if ($this->subCommandMap->executeSubCommand(strtolower($args[0]), $sender)) {
            return true;
        }

        $sender->sendMessage("§c잘못된 명령어입니다.");
        return false;
    }

    // ✅ 자동완성 미리보기 추가
    public function getOverloads(): array {
        return [
            "create" => [
                "description" => "SkyBlock 생성",
                "type" => "string",
                "name" => "create"
            ],
            "delete" => [
                "description" => "SkyBlock 삭제",
                "type" => "string",
                "name" => "delete"
            ],
            "home" => [
                "description" => "SkyBlock으로 이동",
                "type" => "string",
                "name" => "home"
            ],
            "info" => [
                "description" => "SkyBlock 정보 보기",
                "type" => "string",
                "name" => "info"
            ],
        ];
    }

    // ✅ 자동완성 미리보기
    public function getAliases(): array {
        return ["sb"];
    }

    public function getUsage(): string {
        return "/skyblock <create|delete|home|info>";
    }
}
