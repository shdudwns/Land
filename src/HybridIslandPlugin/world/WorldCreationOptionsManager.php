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
        
        switch (strtolower($type)) {
            case "island":
                $seed = mt_rand();
                $options->setGeneratorClass(IslandGenerator::class); // ✅ 클래스 이름(string)으로 수정
                $options->setSeed($seed); // ✅ 랜덤 시드 적용
                break;

            case "gridland":
                $options->setGeneratorClass(GridLandGenerator::class); // ✅ 수정
                $options->setSpawnLocation(new Vector3(0, 65, 0));
                $options->setSeed(mt_rand());
                break;

            case "skyblock":
                $options->setGeneratorClass(SkyBlockGenerator::class); // ✅ 수정
                $options->setSpawnLocation(new Vector3(8, 65, 8));
                $options->setSeed(mt_rand());
                break;

            default:
                throw new \InvalidArgumentException("Unknown world type: $type");
        }

        return $options;
    }
}
