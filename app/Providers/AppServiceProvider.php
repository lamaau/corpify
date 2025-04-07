<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);

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
