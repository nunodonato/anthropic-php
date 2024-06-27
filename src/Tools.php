<?php

namespace NunoDonato\AnthropicAPIPHP;

class Tools
{
    /** @var array<int, array<string, mixed>> */
    private array $tools = [];


    /** @return array<int, array<string, mixed>> */
    public function tools(): array
    {
        return $this->tools;
    }

    /**
     * @param array<string, mixed> $tool
     */
    public function addToolFromArray(array $tool): self
    {
        $required = ['name', 'description', 'input_schema'];
        foreach ($required as $key) {
            if (!array_key_exists($key, $tool)) {
                throw new \InvalidArgumentException('Tool must have a ' . $key . ' key');
            }
        }
        $requiredInput = ['type', 'properties', 'required'];
        foreach ($requiredInput as $key) {
            if (!array_key_exists($key, $tool['input_schema'])) {
                throw new \InvalidArgumentException('Input schema must have a ' . $key . ' key');
            }
        }
        $requiredProperties = ['type', 'description'];
        foreach ($requiredProperties as $key) {
            foreach ($tool['input_schema']['properties'] as $name => $property) {
                if (!array_key_exists($key, $property)) {
                    throw new \InvalidArgumentException('Property ' . $name . ' must have a ' . $key . ' key');
                }
            }
        }
        $this->tools[] = $tool;

        return $this;
    }

    /**
     * @param array<int, array<string, mixed>> $tools
     */
    public function addToolsFromArray(array $tools): self
    {
        foreach ($tools as $i => $tool) {
            if (!is_array($tool)) {
                throw new \InvalidArgumentException('Tool must be an array. Index: ' . $i);
            }
            $this->addToolFromArray($tool);
        }

        return $this;
    }

}