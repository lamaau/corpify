<?php

namespace App\Http\Controllers\Setting;

use App\Actions\Response;
use App\Enums\SettingContext;
use App\Http\Controllers\Controller;
use App\Actions\Setting\SettingAction;
use App\Http\Requests\Setting\SettingRequest;

class AppController extends Controller
{
    public function __invoke(SettingRequest $request)
    {
        $request->getData()->each(function ($value, $key) use ($request) {
            foreach ($value as $index => $item) {
                $context = SettingContext::createArrayContext(SettingContext::APP(), $index);

                $settings = SettingAction::instance()->create($key, $context);

                if (isset($item['file']) && $request->hasFile("$key.$index.file")) {
                    $file = $request->file("$key.$index.file");
                    $filename = $file->store('uploads/settings', 'public');

                    $item['file'] = $filename;
                }

                $settings->fill([
                    'name' => $key,
                    'value' => json_encode($item),
                    'context' => $context,
                    'created_by' => user()->id,
                ]);

                $settings->save();
            }
        });

        return Response::success()->message('Setting successfully updated');
    }
}
