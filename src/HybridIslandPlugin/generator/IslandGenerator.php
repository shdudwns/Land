<?php

namespace HybridIslandPlugin\generator;

use pocketmine\world\ChunkManager;
use pocketmine\math\Vector3;
use pocketmine\world\generator\Generator;
use pocketmine\block\VanillaBlocks;
use pocketmine\utils\Random;
use pocketmine\world\generator\GeneratorOptions;

class IslandGenerator extends Generator {

    public function __construct(GeneratorOptions $options) {
        parent::__construct($options);
    }

    public function getName(): string {
        return "IslandGenerator";
    }

    public function getSettings(): array {
        return ["preset" => "island"];
    }

    public function generateChunk(ChunkManager $world, int $chunkX, int $chunkZ): void {
        // (0, 0) 청크에 섬 생성
        if ($chunkX == 0 && $chunkZ == 0) {
            for ($x = 0; $x <= 15; $x++) {
                for ($z = 0; $z <= 15; $z++) {
                    $world->setBlockStateId($chunkX * 16 + $x, 64, $chunkZ * 16 + $z, VanillaBlocks::GRASS()->getStateId());
                    $world->setBlockStateId($chunkX * 16 + $x, 63, $chunkZ * 16 + $z, VanillaBlocks::DIRT()->getStateId());
                    $world->setBlockStateId($chunkX * 16 + $x, 62, $chunkZ * 16 + $z, VanillaBlocks::DIRT()->getStateId());
                    $world->setBlockStateId($chunkX * 16 + $x, 61, $chunkZ * 16 + $z, VanillaBlocks::STONE()->getStateId());
                    $world->setBlockStateId($chunkX * 16 + $x, 60, $chunkZ * 16 + $z, VanillaBlocks::STONE()->getStateId());
                }
            }
        }
    }

    public function populateChunk(ChunkManager $world, int $chunkX, int $chunkZ): void {
        // 나무 생성 예시
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
