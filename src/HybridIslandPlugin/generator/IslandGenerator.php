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
        // ✅ 청크 가져오기
        $chunk = $world->getChunk($chunkX, $chunkZ);

        // ✅ 스폰 청크일 때만 섬 생성
        if ($chunkX == 0 && $chunkZ == 0) {
            for ($x = 0; $x <= 15; $x++) {
                for ($z = 0; $z <= 15; $z++) {
                    $chunk->setBlock($x, 64, $z, VanillaBlocks::GRASS()->getStateId());
                    $chunk->setBlock($x, 63, $z, VanillaBlocks::DIRT()->getStateId());
                    $chunk->setBlock($x, 62, $z, VanillaBlocks::DIRT()->getStateId());
                    $chunk->setBlock($x, 61, $z, VanillaBlocks::STONE()->getStateId());
                    $chunk->setBlock($x, 60, $z, VanillaBlocks::STONE()->getStateId());
                }
            }
        }

        // ✅ 청크를 생성 완료 상태로 설정
        $chunk->setGenerated();
    }

    public function populateChunk(ChunkManager $world, int $chunkX, int $chunkZ): void {
        // 나무 등 추가 생성 가능
    }

    public function getSpawn(): Vector3 {
        return new Vector3(8, 65, 8);
    }
}
