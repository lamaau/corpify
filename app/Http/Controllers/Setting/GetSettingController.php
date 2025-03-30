<?php

namespace App\Http\Controllers\Setting;

use App\Actions\Response;
use App\Actions\Setting\SettingAction;
use App\Http\Controllers\Controller;
use App\Models\Setting\Setting;

class GetSettingController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        $query = Setting::query()->whereName('app_hero_carousel')->count();
        $context = function () use ($query) {
            $c = [];
            for ($i = 0; $i < $query; $i++) {
                array_push($c, "app.{$i}");
            }

            return $c;
        };

        $settings = SettingAction::instance()
            ->getFromContexts($context())->pluck('value')
            ->transform(fn($row) => json_decode($row))
            ->transform(function ($row) {
                return array_merge((array)$row, ['sort' => (int) $row->sort, 'file' => url($row->file)]);
            })
            ->all();

        return Response::success()->data($settings)->message('Succesfully');
    }
}
