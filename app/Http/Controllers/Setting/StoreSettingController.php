<?php

namespace App\Http\Controllers\Setting;

use App\Actions\Response;
use App\Enums\SettingContext;
use App\Http\Controllers\Controller;
use App\Actions\Setting\SettingAction;
use App\Http\Requests\Setting\SettingRequest;
use Illuminate\Support\Facades\DB;

class StoreSettingController extends Controller
{
    public function __invoke(SettingRequest $request)
    {
        DB::beginTransaction();

        try {
            $request->getData()->each(function ($value, $key) use ($request) {
                $newContexts = collect();

                $existingSettings = SettingAction::instance()->basicQuery()
                    ->whereName($key)->get()
                    ->keyBy('context');

                foreach ($value as $index => $item) {
                    $context = SettingContext::createArrayContext(SettingContext::APP(), $index);
                    $settings = SettingAction::instance()->create($key, $context);

                    if (isset($item['file']) && $request->hasFile("$key.$index.file")) {
                        $file = $request->file("$key.$index.file");
                        $filename = $file->store('uploads/settings', 'public');

                        $item['file'] = $filename;
                    } else {
                        $_settings = SettingAction::instance()->getFromContext($context)->first()->value;
                        $value = json_decode($_settings);
                        $item['file'] = $value->file;
                    }

                    $settings->fill([
                        'name' => $key,
                        'value' => json_encode($item),
                        'context' => $context,
                        'created_by' => user()->id,
                    ]);

                    $settings->save();

                    $newContexts->push($context);
                }

                $contextsToDelete = $existingSettings->keys()->diff($newContexts);

                if ($contextsToDelete->isNotEmpty()) {
                    SettingAction::instance()->getFromContext($contextsToDelete->all())->each->delete();
                }
            });

            DB::commit();

            return Response::success()->message('Setting successfully updated');
        } catch (\Throwable $th) {
            DB::rollBack();

            return Response::error()->message($th->getMessage());
        }
    }
}
