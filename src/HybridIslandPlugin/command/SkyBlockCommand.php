<?php

namespace HybridIslandPlugin\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\plugin\Plugin;
use pocketmine\player\Player;
use pocketmine\command\CommandExecutor;
use HybridIslandPlugin\world\SkyBlockManager;
use HybridIslandPlugin\Main;

class SkyBlockCommand extends Command implements PluginIdentifiableCommand, CommandExecutor {

    private array $subCommands = ["create", "delete", "home", "info"];

    public function __construct() {
        parent::__construct("skyblock", "SkyBlock 관리 명령어", "/skyblock <create|delete|home|info>");
        $this->setPermission("hybridislandplugin.command.skyblock");
    }

    public function execute(CommandSender $sender, string $label, array $args): bool {
        if (!$sender instanceof Player) {
            $sender->sendMessage("§c게임 내에서만 사용 가능합니다.");
            return false;
        }

        if (empty($args[0])) {
            $sender->sendMessage("§a사용 가능한 명령어:");
            foreach ($this->subCommands as $subCommand) {
                $sender->sendMessage("§e/skyblock $subCommand");
            }
            return false;
        }

        switch (strtolower($args[0])) {
            case "create":
                SkyBlockManager::createSkyBlock($sender);
                break;

            case "delete":
                SkyBlockManager::deleteSkyBlock($sender);
                break;

            case "home":
                SkyBlockManager::teleportToSkyBlock($sender);
                break;

            case "info":
                $info = SkyBlockManager::getSkyBlockInfo($sender);
                $sender->sendMessage($info);
                break;

            default:
                $sender->sendMessage("§c잘못된 명령어입니다.");
                break;
        }
        return true;
    }

    public function getPlugin(): Plugin {
        return Main::getInstance();
    }

    // ✅ 명령어 자동완성 미리보기
    public function getAliases(): array {
        return ["sb"];
    }

    public function getUsage(): string {
        return "/skyblock <create|delete|home|info>";
    }

    public function getSubCommands(): array {
        return $this->subCommands;
    }
}
