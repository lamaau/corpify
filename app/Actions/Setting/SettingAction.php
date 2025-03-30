<?php

namespace App\Actions\Setting;

use App\Models\Setting\Setting;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;

class SettingAction
{
    public function __construct(
        private Setting $model
    ) {
        // 
    }

    public static function instance(): static
    {
        return new static(new Setting);
    }

    public function getFormattedSettings($context = 'app', $settingable_type = null, $settingable_id = null, $decrypt = false)
    {
        return $this->formatSettings(
            $this->basicQuery($context, $settingable_type, $settingable_id)
                ->get(['id', 'name', 'value']),
            $decrypt
        );
    }

    public function formatSettings(Collection $settings, $decrypt = false)
    {
        return $settings->reduce(function ($final, $setting) use ($decrypt) {
            $final[$setting->name] = $decrypt && !empty($setting->value) ? decrypt($setting->value) : $setting->value;
            return $final;
        }, []);
    }

    public function basicQuery($context = null, $settingable_type = null, $settingable_id = null)
    {
        return $this->model->newQuery()
            ->when($context, fn(Builder $builder) => $builder->whereIn('context', (array) $context))
            ->when($settingable_type, fn(Builder $builder) => $builder->where('settingable_type', $settingable_type))
            ->when($settingable_id, fn(Builder $builder) => $builder->where('settingable_id', $settingable_id));
    }

    public function findAppSettingWithName(string $name, string $context = 'app')
    {
        return $this->basicQuery($context)
            ->where('name', $name)
            ->first();
    }

    public function create(string $name, string $context, $settingable_type = null, $settingable_id = null)
    {
        return $this->basicQuery($context, $settingable_type, $settingable_id)
            ->where('name', $name)
            ->firstOrNew();
    }

    public function getFromContext($context)
    {
        return $this->model->newQuery()
            ->where('context', $context)
            ->get();
    }

    public function getFromContexts($context)
    {
        return $this->model->newQuery()
            ->whereIn('context', (array) $context)
            ->get();
    }
}
