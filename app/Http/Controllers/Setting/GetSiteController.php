<?php

namespace App\Http\Controllers\Setting;

use App\Actions\Response;
use App\Actions\Setting\SettingAction;
use App\Enums\SettingContext;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GetSiteController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $context = $request->query('context');

        if (!in_array($context, collect(SettingContext::labels())->pluck('value')->all())) {
            return Response::error()->message('Invalid context');
        }

        $query = SettingAction::instance()->getFormattedSettings($context);

        $decoded = collect($query)->transform(function ($value) {
            $decodedValue = json_decode($value, true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($decodedValue)) {
                if (isset($decodedValue[0]) && is_array($decodedValue[0]) && array_key_exists('sort', $decodedValue[0])) {
                    usort($decodedValue, fn($a, $b) => $a['sort'] <=> $b['sort']);
                }

                return $decodedValue;
            }

            return $value;
        });

        return Response::success()->data($decoded)->message('Successfully');
    }
}
