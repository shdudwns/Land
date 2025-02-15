<?php

namespace HybridIslandPlugin\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use HybridIslandPlugin\world\IslandManager;
use HybridIslandPlugin\command\utils\SubCommandMap;
use HybridIslandPlugin\command\utils\CommandParameter;

class IslandCommand extends Command implements Listener {

    private SubCommandMap $subCommandMap;
    private CommandParameter $parameter;

    public function __construct() {
        parent::__construct("island", "섬 관리 명령어", "/island <create|delete|home|info>", ["isl"]);
        $this->setPermission("hybridislandplugin.command.island");

        $this->subCommandMap = new SubCommandMap();
        $this->subCommandMap->registerSubCommand("create", function(Player $sender) {
            IslandManager::createIsland($sender);
        }, "섬 생성");

        $this->subCommandMap->registerSubCommand("delete", function(Player $sender) {
            IslandManager::deleteIsland($sender);
        }, "섬 삭제");

        $this->subCommandMap->registerSubCommand("home", function(Player $sender) {
            IslandManager::teleportToIsland($sender);
        }, "섬으로 이동");

        $this->subCommandMap->registerSubCommand("info", function(Player $sender) {
            $info = IslandManager::getIslandInfo($sender);
            $sender->sendMessage($info);
        }, "섬 정보 보기");

        // ✅ 자동완성 파라미터 설정
        $this->parameter = new CommandParameter("subcommand", $this->subCommandMap->getAllNames());
    }

    public function execute(CommandSender $sender, string $label, array $args): bool {
        if (!$sender instanceof Player) {
            $sender->sendMessage("§c플레이어만 사용 가능합니다.");
            return false;
        }

        if (empty($args[0])) {
            $sender->sendMessage("§a사용 가능한 명령어:");
            foreach ($this->subCommandMap->getAllInfo() as $name => $info) {
                $sender->sendMessage("§e/island $name §7- " . $info["description"]);
            }
            return false;
        }

        $subCommand = strtolower($args[0]);
        if (in_array($subCommand, $this->subCommandMap->getAllNames())) {
            return $this->subCommandMap->executeSubCommand($subCommand, $sender, array_slice($args, 1));
        }

        $sender->sendMessage("§c잘못된 명령어입니다.");
        return false;
    }

    // ✅ 자동완성 데이터 PocketMine-MP 5.x와 연동
    public function getOverloads(): array {
        return [
            [
                "parameters" => [
                    [
                        "name" => $this->parameter->getName(),
                        "type" => "string",
                        "optional" => false,
                        "enum" => $this->parameter->getEnumValues()
                    ]
                ]
            ]
        ];
    }

    // ✅ 플레이어 명령어 실시간 감지
    public function onPlayerCommandPreprocess(PlayerCommandPreprocessEvent $event): void {
        $message = $event->getMessage();
        if (str_starts_with($message, "/island ")) {
            $args = explode(" ", substr($message, 8));
            if (count($args) === 1) {
                $matches = $this->parameter->getAutoComplete($args[0]);
                if (!empty($matches)) {
                    $event->getPlayer()->sendMessage("§7사용 가능한 서브 명령어: §e" . implode(", ", $matches));
                }
            }
        }
    }
}
