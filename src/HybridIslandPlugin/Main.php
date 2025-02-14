<?php

namespace HybridIslandPlugin;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\Config;
use HybridIslandPlugin\command\IslandCommand;
use HybridIslandPlugin\command\GridLandCommand;
use HybridIslandPlugin\command\SkyBlockCommand;
use HybridIslandPlugin\listener\EventListener;
use HybridIslandPlugin\world\WorldManager;
use HybridIslandPlugin\land\LandManager;
use HybridIslandPlugin\util\ConfigManager;
use HybridIslandPlugin\protection\ProtectionManager;

class Main extends PluginBase implements Listener {

    private static Main $instance;
    private Config $messages;

    public static function getInstance(): Main {
        return self::$instance;
    }

    public function onLoad(): void {
        self::$instance = $this;
    }

    public function onEnable(): void {
        $this->getLogger()->info("HybridIslandPlugin 활성화");

        // Config 로드
        $this->saveResource("messages.yml");
        $this->messages = new Config($this->getDataFolder() . "messages.yml", Config::YAML);

        // 명령어 등록
        $this->getServer()->getCommandMap()->registerAll("HybridIslandPlugin", [
            new IslandCommand($this),
            new GridLandCommand($this),
            new SkyBlockCommand($this)
        ]);

        // 이벤트 리스너 등록
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);

        // 매니저 초기화
        WorldManager::init();
        LandManager::init();
        ProtectionManager::init();
    }

    public function onDisable(): void {
        $this->getLogger()->info("HybridIslandPlugin 비활성화");
    }

    public function getMessages(): Config {
        return $this->messages;
    }
}
