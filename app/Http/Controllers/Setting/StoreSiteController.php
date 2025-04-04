<?php

namespace App\Http\Controllers\Setting;

use App\Actions\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Actions\Setting\SettingAction;
use App\Http\Requests\Setting\SiteRequest;

class StoreSiteController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(SiteRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $request->getData()->each(function ($value, $key) use ($request) {
                $context = $request->getContext();
                $settings = SettingAction::instance()->create($key, $context);

                $settings->fill([
                    'name' => $key,
                    'value' => is_array($value) ? json_encode($value) : $value,
                    'context' => $context,
                    'created_by' => user()->id,
                ]);

                $settings->save();
            });

            return Response::success()->message('Successfully');
        });
    }
}
