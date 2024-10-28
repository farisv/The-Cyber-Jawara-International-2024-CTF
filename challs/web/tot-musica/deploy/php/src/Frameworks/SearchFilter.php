<?php

namespace App\Frameworks;

use Illuminate\Database\Eloquent\Builder;

class SearchFilter
{
    /**
     * @param Builder $query The query to apply filters to.
     * @param array $params The filters to apply (key => value).
     *
     * @return Builder The modified query.
     */
    public static function applyFilters(Builder $query, array $params, int $user_id): Builder
    {
        foreach ($params as $key => $value) {
            if (strpos($key, '.') !== false) {
                $relations = explode('.', $key);
                if (count($relations) === 2) {
                    list($relation, $field) = $relations;
                    $query->whereHas($relation, function ($q) use ($field, $value, $user_id) {
                        $q->where($field, 'LIKE', '%' . $value . '%');
                    });
                }
            } else {
                $query->where($key, 'LIKE', '%' . $value . '%');
            }
        }

        $query->where('user_id', '=', $user_id);


        return $query;
    }
}
