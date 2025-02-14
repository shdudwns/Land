<?php

namespace HybridIslandPlugin;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use HybridIslandPlugin\generator\IslandGenerator;
use HybridIslandPlugin\generator\GridLandGenerator;
use HybridIslandPlugin\generator\SkyBlockGenerator;
use HybridIslandPlugin\config\ConfigManager;
use pocketmine\world\generator\GeneratorManager;
use pocketmine\world\WorldManager;
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

        $generatorManager = $this->getServer()->getGeneratorManager();

    // ✅ IslandGenerator 등록
    $generatorManager->addGenerator(IslandGenerator::class, "island", function(string $input): bool {
        return preg_match('/^[a-zA-Z0-9_]+$/', $input) === 1;
    });

    // ✅ GridLandGenerator 등록
    $generatorManager->addGenerator(GridLandGenerator::class, "gridland", function(string $input): bool {
        return preg_match('/^[a-zA-Z0-9_]+$/', $input) === 1;
    });

    // ✅ SkyBlockGenerator 등록
    $generatorManager->addGenerator(SkyBlockGenerator::class, "skyblock", function(string $input): bool {
        return preg_match('/^[a-zA-Z0-9_]+$/', $input) === 1;
    });
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
