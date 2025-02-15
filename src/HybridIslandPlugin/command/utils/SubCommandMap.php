<?php

namespace HybridIslandPlugin\command\utils;

use pocketmine\player\Player;
use pocketmine\command\CommandSender;

class SubCommandMap {
    private array $subCommands = [];

    // ✅ 서브 명령어 등록
    public function registerSubCommand(string $name, callable $callback, string $description): void {
        $this->subCommands[$name] = [
            "callback" => $callback,
            "description" => $description
        ];
    }

    // ✅ 서브 명령어 실행
    public function executeSubCommand(string $name, CommandSender $sender, array $args): bool {
        if (isset($this->subCommands[$name])) {
            $callback = $this->subCommands[$name]["callback"];
            $callback($sender, $args);
            return true;
        }
        return false;
    }

    // ✅ 서브 명령어 이름 리스트 반환
    public function getAllNames(): array {
        return array_keys($this->subCommands);
    }

    // ✅ 자동완성용 문자열 반환
    public function getAutoComplete(): string {
        return implode(" ", $this->getAllNames());
    }

    public function getAllInfo(): array {
    $info = [];
    foreach ($this->subCommands as $name => $data) {
        $info[$name] = [
            "description" => $data["description"]
        ];
    }
    return $info;
}
}
