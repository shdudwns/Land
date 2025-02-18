<?php

namespace HybridIslandPlugin\world;

use pocketmine\world\WorldCreationOptions;
use HybridIslandPlugin\generator\IslandGenerator;
use HybridIslandPlugin\generator\GridLandGenerator;
use HybridIslandPlugin\generator\SkyBlockGenerator;

class WorldCreationOptionsManager {

    public static function getOptions(string $type): WorldCreationOptions {
        $options = new WorldCreationOptions();
        $options->setSeed(mt_rand());

        switch (strtolower($type)) {
            case "island":
                $options->setGeneratorClass(IslandGenerator::class);
                break;

            case "gridland":
                $options->setGeneratorClass(GridLandGenerator::class);
                break;

            case "skyblock":
                $options->setGeneratorClass(SkyBlockGenerator::class);
                break;

            default:
                throw new \InvalidArgumentException("Unknown world type: $type");
        }

        return $options;
    }
}
