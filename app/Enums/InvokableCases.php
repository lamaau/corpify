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

    public static function labels(?string $search = null): array
    {
        $labels = collect(self::cases())
            ->map(fn($row) => $row->attributes());

        if ($search) {
            $labels = $labels->filter(fn($item) => str_contains(strtolower($item['name']), strtolower($search)));
        }

        return $labels->values()->all();
    }

    public function attributes(): array
    {
        return [
            'name' => method_exists($this, 'label') ? $this->label() : $this->name,
            'value' => method_exists($this, 'value') ? $this->value() : $this->value
        ];
    }
}
