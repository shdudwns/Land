<?php

namespace HybridIslandPlugin\generator;

use pocketmine\world\ChunkManager;
use pocketmine\world\World;
use pocketmine\math\Vector3;
use pocketmine\world\generator\Generator;
use pocketmine\block\Block;
use pocketmine\utils\Random;
use pocketmine\world\generator\GeneratorOptions;

class IslandGenerator extends Generator {

    public function __construct(int $seed, string $options = "") {
        parent::__construct($seed, $options);
    }

    public function getName(): string {
        return "IslandGenerator";
    }

    public function getSettings(): array {
    return ["preset" => "island"];
}

    public function generateChunk(ChunkManager $world, int $chunkX, int $chunkZ): void {
        $chunk = $world->getChunk($chunkX, $chunkZ);
        if ($chunkX == 0 && $chunkZ == 0) {
            for ($x = 0; $x <= 15; $x++) {
                for ($z = 0; $z <= 15; $z++) {
                    $chunk->setBlock($x, 64, $z, Block::GRASS);
                    $chunk->setBlock($x, 63, $z, Block::DIRT);
                    $chunk->setBlock($x, 62, $z, Block::DIRT);
                    $chunk->setBlock($x, 61, $z, Block::STONE);
                    $chunk->setBlock($x, 60, $z, Block::STONE);
                }
            }
        }
        $world->setChunk($chunkX, $chunkZ, $chunk);
    }

    public function populateChunk(ChunkManager $world, int $chunkX, int $chunkZ): void {
        // 나무 등 추가 생성 가능
    }

    public function getSpawn(): Vector3 {
        return new Vector3(8, 65, 8);
    }
}
