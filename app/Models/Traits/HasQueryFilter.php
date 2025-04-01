<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasQueryFilter
{
    public function scopeApplySearchWhen(Builder $query, ?string $value, array $columns): void
    {
        $query->when($value, function (Builder $query) use ($columns, $value) {
            $query->where(function (Builder $query) use ($columns, $value) {
                foreach ($columns as $column) {
                    if (str_contains($column, '.')) {
                        [$relation, $relationColumn] = explode('.', $column, 2);
                        $query->orWhereHas($relation, function (Builder $subQuery) use ($relationColumn, $value) {
                            $subQuery->where($relationColumn, 'LIKE', "%{$value}%");
                        });
                    } else {
                        $query->orWhere($column, 'LIKE', "%{$value}%");
                    }
                }
            });
        });
    }

    public function scopeApplyFilterWhen(Builder $query, array |string | null $value, string $column): void
    {
        $query->when($value, function (Builder $query) use ($column, $value) {
            if (is_array($value)) {
                return $query->whereIn($column, $value);
            }

            if (is_string($value) && str_contains($value, ',')) {
                return $query->whereIn($column, explode(',', $value));
            }

            return $query->where($column, $value);
        });
    }
}
