<?php

namespace HybridIslandPlugin\command\utils;

use Closure;
use pocketmine\player\Player;

class SubCommandMap {

    private array $subCommands = [];

    // ✅ 서브 명령어 등록 (설명과 사용법 추가)
    public function registerSubCommand(string $name, Closure $callback, string $description, string $usage): void {
        $this->subCommands[$name] = [
            "callback" => $callback,
            "description" => $description,
            "usage" => $usage
        ];
    }

    // ✅ 서브 명령어 실행
    public function executeSubCommand(string $name, Player $sender): bool {
        if (isset($this->subCommands[$name])) {
            $callback = $this->subCommands[$name]["callback"];
            $callback($sender);
            return true;
        }
        return false;
    }

    // ✅ 모든 서브 명령어 이름 반환
    public function getAllNames(): array {
        return array_keys($this->subCommands);
    }

    // ✅ 서브 명령어 설명 및 사용법 가져오기
    public function getSubCommandInfo(string $name): array {
        return $this->subCommands[$name] ?? [];
    }

    // ✅ 모든 서브 명령어 정보 가져오기
    public function getAllInfo(): array {
        return $this->subCommands;
    }
}
