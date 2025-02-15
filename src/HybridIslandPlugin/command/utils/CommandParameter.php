<?php

namespace HybridIslandPlugin\command\utils;

class CommandParameter {

    private string $name;
    private array $options;

    public function __construct(string $name, array $options = []) {
        $this->name = $name;
        $this->options = $options;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getOptions(): array {
        return $this->options;
    }

    public function getAutoComplete(string $input): array {
        $matches = [];
        foreach ($this->options as $option) {
            if (stripos($option, $input) === 0) {
                $matches[] = $option;
            }
        }
        return $matches;
    }
}
