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
                    'context' => $context,
                    'created_by' => user()->id,
                ])->save();

                if (is_array($value) && is_array($value[0] ?? null)) {
                    $final = [];

                    foreach ($value as $index => $item) {
                        $fileUrl = null;
                        // keep in mind, always using `file` as key if you want to upload file
                        if (isset($item['file']) && $item['file'] instanceof \Illuminate\Http\UploadedFile) {
                            $media = $settings->addMedia($item['file'])->toMediaCollection($key);
                            $fileUrl = $media->getUrl();

                            $final[] = [
                                ...collect($item)->except('file'),
                                'file' => $fileUrl,
                            ];
                        } else {
                            $final[] = $item;
                        }
                    }

                    $settings->update(['value' => json_encode($final)]);
                } elseif ($value instanceof \Illuminate\Http\UploadedFile) {
                    $settings->clearMediaCollection($key);
                    $media = $settings->addMedia($value)->toMediaCollection($key);
                    $settings->update(['value' => $media->getUrl()]);
                } else {
                    $settings->update(['value' => is_array($value) ? json_encode($value) : $value]);
                }
            });

            return Response::success()->message('Successfully');
        });
    }
}
