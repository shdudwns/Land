<?php

namespace HybridIslandPlugin\command\utils;

class CommandParameter {
    public string $name;
    public int $type;
    public bool $optional;
    public array $enumValues;

    public function __construct(string $name, int $type, bool $optional = false, array $enumValues = []) {
        $this->name = $name;
        $this->type = $type;
        $this->optional = $optional;
        $this->enumValues = $enumValues;
    }
}
