<?php

namespace HybridIslandPlugin\generator;

use pocketmine\world\ChunkManager;
use pocketmine\world\World;
use pocketmine\math\Vector3;
use pocketmine\world\generator\Generator;
use pocketmine\block\Block;
use pocketmine\utils\Random;

class SkyBlockGenerator extends Generator {

    public function __construct(array $options = []) {
        parent::__construct($options);
    }

    public function getName(): string {
        return "SkyBlockGenerator";
    }

    public function generateChunk(ChunkManager $world, int $chunkX, int $chunkZ): void {
        $chunk = $world->getChunk($chunkX, $chunkZ);
        if ($chunkX == 0 && $chunkZ == 0) {
            $chunk->setBlock(8, 64, 8, Block::GRASS);
            $chunk->setBlock(8, 63, 8, Block::DIRT);
            $chunk->setBlock(8, 62, 8, Block::DIRT);
            $chunk->setBlock(8, 61, 8, Block::STONE);
        }
        $world->setChunk($chunkX, $chunkZ, $chunk);
    }

    public function populateChunk(ChunkManager $world, int $chunkX, int $chunkZ): void {
        // SkyBlock 초기 자원 추가 가능
    }

    public function getSpawn(): Vector3 {
        return new Vector3(8, 65, 8);
    }
}
