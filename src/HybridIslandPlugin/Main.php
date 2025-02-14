<?php

namespace HybridIslandPlugin;

use pocketmine\plugin\PluginBase;
use HybridIslandPlugin\config\ConfigManager;
use HybridIslandPlugin\command\IslandCommand;
use HybridIslandPlugin\command\GridLandCommand;
use HybridIslandPlugin\command\SkyBlockCommand;

class Main extends PluginBase {

    private static Main $instance;

    public static function getInstance(): Main {
        return self::$instance;
    }

    protected function onEnable(): void {
        self::$instance = $this;

        // ✅ ConfigManager 초기화
        ConfigManager::init();

        // ✅ 명령어 등록
        $this->getServer()->getCommandMap()->registerAll("HybridIslandPlugin", [
            new IslandCommand(),
            new GridLandCommand(),
            new SkyBlockCommand()
        ]);

        $this->getLogger()->info("HybridIslandPlugin 활성화 완료!");
    }

    protected function onDisable(): void {
        $this->getLogger()->info("HybridIslandPlugin 비활성화 완료!");
    }
}
