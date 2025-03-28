<?php

namespace App\Enums;

trait InvokableCases
{
    public function __invoke()
    {
        return $this->value;
    }

    public static function __callStatic(mixed $name, mixed $arguments): mixed
    {
        return collect(self::cases())->firstWhere('name', $name)->value;
    }

    public function attributes(): array
    {
        return [
            'name' => $this->name,
            'value' => $this->value,
        ];
    }
}
