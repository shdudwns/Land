<?php

namespace HybridIslandPlugin;

use pocketmine\plugin\PluginBase;
use pocketmine\world\generator\GeneratorManager;
use pocketmine\event\Listener;
use HybridIslandPlugin\generator\IslandGenerator;
use HybridIslandPlugin\generator\GridLandGenerator;
use HybridIslandPlugin\generator\SkyBlockGenerator;
use HybridIslandPlugin\config\ConfigManager;
use HybridIslandPlugin\world\WorldManager;
use HybridIslandPlugin\command\IslandCommand;
use HybridIslandPlugin\command\GridLandCommand;
use HybridIslandPlugin\command\SkyBlockCommand;
use HybridIslandPlugin\listener\EventListener;

class Main extends PluginBase implements Listener {

    private static Main $instance;

    public static function getInstance(): Main {
        return self::$instance;
    }

    public function onLoad(): void {
        self::$instance = $this;
    }

    public function onEnable(): void {
        // ✅ Config 및 WorldManager 초기화
        ConfigManager::init();
        WorldManager::init();

        // ✅ Generator 등록
        GeneratorManager::getInstance()->addGenerator(IslandGenerator::class, "island", true);
        GeneratorManager::getInstance()->addGenerator(GridLandGenerator::class, "gridland", true);
        GeneratorManager::getInstance()->addGenerator(SkyBlockGenerator::class, "skyblock", true);

        // ✅ 명령어 등록
        $this->getServer()->getCommandMap()->registerAll("HybridIslandPlugin", [
            new IslandCommand(),
            new GridLandCommand(),
            new SkyBlockCommand()
        ]);

        // ✅ 이벤트 리스너 등록
        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
    }

    public function onDisable(): void {
        // ✅ 플러그인 비활성화 시 데이터 저장
        ConfigManager::saveAll();
    }
}
