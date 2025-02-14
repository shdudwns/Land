<?php

namespace HybridIslandPlugin\generator;

use pocketmine\world\ChunkManager;
use pocketmine\world\World;
use pocketmine\math\Vector3;
use pocketmine\world\generator\Generator;
use pocketmine\block\Block;
use pocketmine\utils\Random;

class GridLandGenerator extends Generator {

    private int $landWidth;
    private int $roadWidth;

    public function __construct(array $options = []) {
        parent::__construct($options);
        $this->landWidth = $options["landWidth"] ?? 32;
        $this->roadWidth = $options["roadWidth"] ?? 5;
    }

    public function getName(): string {
        return "GridLandGenerator";
    }

    public function generateChunk(ChunkManager $world, int $chunkX, int $chunkZ): void {
        $chunk = $world->getChunk($chunkX, $chunkZ);

        for ($x = 0; $x <= 15; $x++) {
            for ($z = 0; $z <= 15; $z++) {
                $worldX = $chunkX * 16 + $x;
                $worldZ = $chunkZ * 16 + $z;

                $gridX = $worldX % ($this->landWidth + $this->roadWidth);
                $gridZ = $worldZ % ($this->landWidth + $this->roadWidth);

                if ($gridX < $this->roadWidth || $gridZ < $this->roadWidth) {
                    $chunk->setBlock($x, 64, $z, Block::STONE);
                } else {
                    $chunk->setBlock($x, 64, $z, Block::GRASS);
                    $chunk->setBlock($x, 63, $z, Block::DIRT);
                    $chunk->setBlock($x, 62, $z, Block::DIRT);
                    $chunk->setBlock($x, 61, $z, Block::STONE);
                }
            }
        }
        $world->setChunk($chunkX, $chunkZ, $chunk);
    }

    public function populateChunk(ChunkManager $world, int $chunkX, int $chunkZ): void {
        // 건물 또는 나무 추가 가능
    }

    public function getSpawn(): Vector3 {
        return new Vector3(8, 65, 8);
    }
}
