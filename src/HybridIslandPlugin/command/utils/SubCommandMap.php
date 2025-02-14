<?php

// SubCommandMap.php
namespace HybridIslandPlugin\command\utils;

use Closure;
use pocketmine\player\Player;

class SubCommandMap {

    private array $subCommands = [];

    // ✅ 서브 명령어 등록
    public function registerSubCommand(string $name, Closure $callback): void {
        $this->subCommands[$name] = $callback;
    }

    // ✅ 서브 명령어 실행
    public function executeSubCommand(string $name, Player $sender): bool {
        if (isset($this->subCommands[$name])) {
            $callback = $this->subCommands[$name];
            $callback($sender);
            return true;
        }
        return false;
    }

    // ✅ 모든 서브 명령어 이름만 반환
    public function getAllNames(): array {
        return array_keys($this->subCommands);
    }
}
