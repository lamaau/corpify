<?php

namespace App\Actions\Setting;

use App\Models\Setting\Setting;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Auth\Authenticatable;

class SettingAction
{
    private ?string $context = 'app';

    private Model|Authenticatable|null $settingAble = null;

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

    public function getDeliverySettingLists($context = null, $settingable_type = null, $settingable_id = null)
    {
        return $this->formatSettings(
            $this->basicQuery($context, $settingable_type, $settingable_id)
                ->get(['id', 'name', 'value']),
            true
        );
    }

    public function getFromContexts($context)
    {
        return $this->model->newQuery()
            ->whereIn('context', (array) $context)
            ->get();
    }

    public function getDefaultMailKey($key = 'default_mail', $settingable_type = null, $settingable_id = null)
    {
        return $this->model->newQuery()
            ->select(['id', 'name', 'value', 'context'])
            ->where('name', $key)
            ->where('settingable_type', $settingable_type)
            ->where('settingable_id', $settingable_id)
            ->first();
    }

    public function getFromKey($key)
    {
        [$setting_able_id, $setting_able_type] = $this->getSettingAble();
        $context = $this->getContext();

        $setting = $this->basicQuery($context, $setting_able_type, $setting_able_id)
            ->where('name', $key)
            ->select(['name', 'value'])
            ->first();

        return $setting ? $this->formatSettings(collect([$setting]))[$key] ?? '' : '';
    }

    public function getFromKeys(array $keys)
    {
        [$setting_able_id, $setting_able_type] = $this->getSettingAble();
        $context = $this->getContext();

        return $this->formatSettings(
            $this->basicQuery($context, $setting_able_type, $setting_able_id)
                ->whereIn('name', $keys)
                ->select(['name', 'value'])
                ->get()
        );
    }

    public function getSettingAble()
    {
        $settingable_type = $this->settingAble instanceof Model || $this->settingAble instanceof Authenticatable
            ? get_class($this->settingAble)
            : null;

        $settingable_id = $this->settingAble?->id ?? null;

        return [$settingable_id, $settingable_type];
    }

    public function setSettingAble($settingAble)
    {
        $this->settingAble = $settingAble;
        return $this;
    }

    public function getContext(): string
    {
        return $this->context ?? 'app';
    }

    public function setContext($context)
    {
        $this->context = $context;
        return $this;
    }
}
