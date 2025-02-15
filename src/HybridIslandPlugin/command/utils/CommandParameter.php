<?php

namespace HybridIslandPlugin\command\utils;

class CommandParameter {

    private string $name;
    private array $enumValues;

    public function __construct(string $name, array $enumValues) {
        $this->name = $name;
        $this->enumValues = $enumValues;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getEnumValues(): array {
        return $this->enumValues;
    }

    // ✅ 자동완성 데이터 반환
    public function getAutoComplete(string $input): array {
        $matches = [];
        foreach ($this->enumValues as $value) {
            if (str_starts_with($value, $input)) {
                $matches[] = $value;
            }
        }
        return $matches;
    }
}
