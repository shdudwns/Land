<?php

namespace HybridIslandPlugin\world;

use pocketmine\world\WorldCreationOptions;
use pocketmine\math\Vector3;
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
                $options->setSpawnLocation(new Vector3(8, 65, 8));
                break;

            case "gridland":
                $options->setGeneratorClass(GridLandGenerator::class);
                $options->setSpawnLocation(new Vector3(0, 65, 0));
                break;

            case "skyblock":
                $options->setGeneratorClass(SkyBlockGenerator::class);
                $options->setSpawnLocation(new Vector3(8, 65, 8));
                break;

            default:
                throw new \InvalidArgumentException("Unknown world type: $type");
        }

        return $options;
    }
}
