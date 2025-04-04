<?php

namespace App\Http\Controllers\Setting;

use App\Actions\Response;
use Illuminate\Http\Request;
use App\Enums\SettingContext;
use App\Http\Controllers\Controller;
use App\Actions\Setting\SettingAction;
use App\Http\Requests\Setting\SortRequest;

class SortSiteController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(SortRequest $request)
    {
        $query = SettingAction::instance()->getFormattedSettings($request->getContext());

        $decoded = collect($query)->transform(function ($value) {
            $decodedValue = json_decode($value, true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($decodedValue)) {
                // If it's a list of objects with 'sort'
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
