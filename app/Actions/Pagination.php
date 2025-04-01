<?php

namespace App\Actions;

use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class Pagination
{
    public static function make(array | Collection $items, int $perPage = 10): LengthAwarePaginator
    {
        $request = request();

        $items = collect($items);
        $page = $request->query('page', 1);
        $total = $items->count();

        return new LengthAwarePaginator(
            $items->forPage($page, $perPage),
            $total,
            $perPage,
            $page,
            ['path' => $request->url()]
        );
    }
}
