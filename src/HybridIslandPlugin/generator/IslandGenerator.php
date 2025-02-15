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

        // ✅ (0, 0) 청크에 섬 생성
        if ($chunkX == 0 && $chunkZ == 0) {
            for ($x = 0; $x <= 15; $x++) {
                for ($z = 0; $z <= 15; $z++) {
                    $chunk->setFullBlock($x, 64, $z, VanillaBlocks::GRASS()->getStateId());
                    $chunk->setFullBlock($x, 63, $z, VanillaBlocks::DIRT()->getStateId());
                    $chunk->setFullBlock($x, 62, $z, VanillaBlocks::DIRT()->getStateId());
                    $chunk->setFullBlock($x, 61, $z, VanillaBlocks::STONE()->getStateId());
                    $chunk->setFullBlock($x, 60, $z, VanillaBlocks::STONE()->getStateId());
                }
            }
        }
    }

    public function populateChunk(ChunkManager $world, int $chunkX, int $chunkZ): void {
        // ✅ 섬 위에 나무 생성 (예시)
        if ($chunkX == 0 && $chunkZ == 0) {
            $world->setBlockAt(8, 65, 8, VanillaBlocks::OAK_LOG());
            $world->setBlockAt(8, 66, 8, VanillaBlocks::OAK_LOG());
            $world->setBlockAt(8, 67, 8, VanillaBlocks::OAK_LEAVES());
            $world->setBlockAt(7, 67, 8, VanillaBlocks::OAK_LEAVES());
            $world->setBlockAt(9, 67, 8, VanillaBlocks::OAK_LEAVES());
            $world->setBlockAt(8, 67, 7, VanillaBlocks::OAK_LEAVES());
            $world->setBlockAt(8, 67, 9, VanillaBlocks::OAK_LEAVES());
        }
    }

    public function getSpawn(): Vector3 {
        return new Vector3(8, 65, 8);
    }
}
