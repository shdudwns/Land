<?php

namespace HybridIslandPlugin\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\plugin\Plugin;
use pocketmine\player\Player;
use pocketmine\command\CommandExecutor;
use pocketmine\command\utils\SubCommandMap;
use HybridIslandPlugin\world\IslandManager;
use HybridIslandPlugin\Main;

class IslandCommand extends Command implements PluginIdentifiableCommand, CommandExecutor {

    private SubCommandMap $subCommands;

    public function __construct() {
        parent::__construct("island", "섬 관리 명령어", "/island <create|delete|home|info>", ["isl"]);
        $this->setPermission("hybridislandplugin.command.island");

        // ✅ 서브 명령어 매핑
        $this->subCommands = new SubCommandMap();
        $this->subCommands->registerSubCommand("create", function(Player $sender) {
            IslandManager::createIsland($sender);
        });
        $this->subCommands->registerSubCommand("delete", function(Player $sender) {
            IslandManager::deleteIsland($sender);
        });
        $this->subCommands->registerSubCommand("home", function(Player $sender) {
            IslandManager::teleportToIsland($sender);
        });
        $this->subCommands->registerSubCommand("info", function(Player $sender) {
            IslandManager::showIslandInfo($sender);
        });
    }

    public function execute(CommandSender $sender, string $label, array $args): bool {
        if (!$sender instanceof Player) {
            $sender->sendMessage("§c플레이어만 사용 가능합니다.");
            return false;
        }

        if (count($args) === 0) {
            // ✅ 하위 명령어 목록 출력
            $sender->sendMessage("§a사용 가능한 명령어:");
            foreach ($this->subCommands->getAll() as $name => $callback) {
                $sender->sendMessage("§e/island $name");
            }
            return true;
        }

        $subCommand = strtolower(array_shift($args));
        if ($this->subCommands->executeSubCommand($subCommand, $sender)) {
            return true;
        }

        $sender->sendMessage("§c잘못된 명령어입니다.");
        return false;
    }

    public function getPlugin(): Plugin {
        return Main::getInstance();
    }

    public function getAliases(): array {
        return ["isl"];
    }
}
