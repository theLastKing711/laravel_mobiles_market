<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

class BuilderMacrosServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {

        Builder::macro('firstWhereId', function (string|int $id): Model|static|null {

            /** @var Builder $this */

            return $this->firstWhere(
                'id',
                $id
            );
        });

        Collection::macro('firstWhereId', function (string|int $id): Model|static|null {

            /** @var Collection $this */

            return $this->firstWhere(
                'id',
                $id
            );
        });

        Builder::macro('whereLike', function (string $field, string $searchTerm): Builder {

            /** @var Builder $this */

            return $this->where(
                $field,
                'LIKE',
                '%'.$searchTerm.'%',
            );
        });

        Builder::macro('whereAnyLike', function (array $fields, string $searchTerm): Builder {

            /** @var Builder $this */

            return $this->whereAny(
                $fields,
                'LIKE',
                '%'.$searchTerm.'%',
            );
        });

        Builder::macro('whereIdIn', function (Collection|array $ids): Builder {

            /** @var Builder $this */

            return $this->whereIn(
                'id',
                $ids
            );
        });

        Collection::macro('whereIdIn', function (Collection|array $ids): Collection {

            /** @var Collection $this */

            return $this->whereIn(
                'id',
                $ids
            );
        });

        Builder::macro('orderByDynamic', function (string $sort_field, string $sort_value): Builder {

            /** @var Builder $this */

            return $sort_value === 'asc' ?
                $this->orderBy($sort_field)
                :
                $this->orderByDesc($sort_field);
        });

        Collection::macro('selectMany', function (string $relation): Collection {

            /** @var Collection $this */

            return $this->pluck($relation)->flatten();
        });

        Collection::macro('paginate', macro: function (int $perPage, $total = null, $page = null, $pageName = 'page') {
            $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);

            /** @var Builder $this description */

            return new LengthAwarePaginator(
                $total ? $this : $this->forPage($page, $perPage)->values(),
                $total ?: $this->count(),
                $perPage,
                $page,
                [
                    'path' => LengthAwarePaginator::resolveCurrentPath(),
                    'pageName' => $pageName,
                ]
            );
        });

        // Collection::macro(
        //     'at',
        //     function (int $index) {

        //         /** @var Collection<int, static> $this */
        //         $x = $this->where($index)->first();

        //         return $x;
        //     }
        // );

        // EloquentCollection::macro('selectMany', function (string $relation): Collection {

        //     /** @var EloquentCollection $this */

        //     return $this->pluck($relation)->flatten();
        // });
    }
}
