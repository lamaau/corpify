<?php

namespace App\Http\Controllers\Guest\Site;

use App\Actions\Response;
use Illuminate\Http\Request;
use App\Enums\SettingContext;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;

class ConfigController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $context = $request->query('context');
        $contexts = collect(SettingContext::labels())->pluck('value');

        if ($context && in_array($context, $contexts->all())) {
            return Response::success()->data(SettingContext::getByContext($context))->message('Successfully');
        }

        $query = $contexts->map(fn(string $context) => SettingContext::getByContext($context))->filter(fn(Collection $value) => count($value))
            ->mapWithKeys(function ($value, $key) use ($contexts) {
                if (array_key_exists($key, $contexts->all())) {
                    return [$contexts[$key] => $value && !is_array($value->first()) ? $value : $value->first()];
                }
            });

        return Response::success()->data($query)->message('Successfully');
    }
}
