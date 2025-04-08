<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class RequestMacroServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Request::macro('mapSearchFieldsWithRelations', function (array $columns, array $relationColumns): array {
            /** @var \Illuminate\Http\Request $this */
            $relations = $this->getRelationsRequest();

            return array_merge(
                $columns,
                collect($relationColumns)
                    ->only($relations)
                    ->flatMap(fn($cols) => (array) $cols)
                    ->toArray()
            );
        });

        Request::macro('getRelationsRequest', function (): array {
            /** @var \Illuminate\Http\Request $this */
            $raw = $this->query('withRelations');

            if (!$raw) {
                return [];
            }

            return collect(str($raw)->trim('[]')->explode(','))
                ->map(fn($item) => trim($item))
                ->filter()
                ->unique()
                ->values()
                ->toArray();
        });
    }
}
