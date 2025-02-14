<?php

namespace HybridIslandPlugin;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use HybridIslandPlugin\config\ConfigManager;
use HybridIslandPlugin\world\WorldManager;
use HybridIslandPlugin\command\IslandCommand;
use HybridIslandPlugin\command\GridLandCommand;
use HybridIslandPlugin\command\SkyBlockCommand;
use HybridIslandPlugin\generator\GridLandGenerator;
use HybridIslandPlugin\generator\SkyBlockGenerator;

class Main extends PluginBase implements Listener {

    private static Main $instance;
    private ConfigManager $configManager;
    private WorldManager $worldManager;

    public static function getInstance(): Main {
        return self::$instance;
    }

    public function onEnable(): void {
        self::$instance = $this;

        ConfigManager::init();
        
        $this->configManager = new ConfigManager($this);
        $this->worldManager = new WorldManager($this);

        $this->getServer()->getCommandMap()->registerAll("hybridisland", [
            new IslandCommand($this),
            new GridLandCommand($this),
            new SkyBlockCommand($this)
        ]);

        $this->getServer()->getPluginManager()->registerEvents($this, $this);

        $this->getLogger()->info("HybridIslandPlugin 활성화 완료");
    }

    public function getConfigManager(): ConfigManager {
        return $this->configManager;
    }

    public function getWorldManager(): WorldManager {
        return $this->worldManager;
    }
}
