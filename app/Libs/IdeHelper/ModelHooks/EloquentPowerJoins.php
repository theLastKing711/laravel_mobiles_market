<?php

namespace App\Libs\IdeHelper\ModelHooks;

use App\Data\Shared\ModelwithPivotCollection;
use App\Models\User;
use Barryvdh\LaravelIdeHelper\Console\ModelsCommand;
use Barryvdh\LaravelIdeHelper\Contracts\ModelHookInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;

class EloquentPowerJoins implements ModelHookInterface
{
    public function run(ModelsCommand $command, Model $model): void
    {

        $joinRelationReturnType =
            Builder::class . '<static>';


        $command->setMethod(
            'joinRelationship',
            $joinRelationReturnType,
            [
                'string $relations',
                '\Closure(Illuminate\Database\Query\JoinClause $join)|array $join_callback_or_array'
            ]
        );

        $joinHasReturnType =
            Builder::class . '<static>';

        $command->setMethod(
            'powerJoinHas',
            $joinHasReturnType
            ,
            [
                'string $relations',
                'mixed operater',
                'mixed value'
            ]
        );

        $joinWhereHasReturnType =
            Builder::class . '<static>';

        $command->setMethod(
            'powerJoinWhereHas',
            $joinWhereHasReturnType,
            [
                'string $relations',
                '\Closure(Illuminate\Database\Query\JoinClause $join)|array $join_callback_or_array'
            ]
        );

        $orderByReturnType =
            Builder::class . '<static>';

        $command->setMethod(
            'orderByPowerJoins',
            $orderByReturnType,
            [
                'string|array<string, \Illuminate\Contracts\Database\Query\Expression> $column'
            ]
        );

        $command->setMethod(
            'orderByLeftPowerJoins',
            $orderByReturnType,
            [
                'string|array<string, \Illuminate\Contracts\Database\Query\Expression> $column'
            ]
        );

        $orderByCountReturnType =
        Builder::class . '<static>';

        $command->setMethod(
            'orderByPowerJoinsCount',
            $orderByCountReturnType,
            [
                'string $column',
                'string|null $order'
            ]
        );

        $command->setMethod(
            'orderByLeftPowerJoinsCount',
            $orderByCountReturnType,
            [
                'string $column',
                'string|null $order'
            ]
        );


        $orderByAverageReturnType =
        Builder::class . '<static>';

        $command->setMethod(
            'orderByPowerJoinsAvg',
            $orderByAverageReturnType,
            [
                'string $column',
                'string|null $order'
            ]
        );

        $command->setMethod(
            'orderByPowerLeftJoinsAvg',
            $orderByAverageReturnType,
            [
                'string $column',
                'string|null $order'
            ]
        );

        $orderBySumReturnType =
        Builder::class . '<static>';

        $command->setMethod(
            'orderByPowerJoinsSum',
            $orderBySumReturnType,
            [
                'string $column',
                'string|null $order'
            ]
        );

        $command->setMethod(
            'orderByPowerLeftJoinsSum',
            $orderBySumReturnType,
            [
                'string $column',
                'string|null $order'
            ]
        );




        $orderByMinReturnType =
        Builder::class . '<static>';

        $command->setMethod(
            'orderByPowerJoinsMin',
            $orderByMinReturnType,
            [
                'string $column',
                'string|null $order'
            ]
        );

        $command->setMethod(
            'orderByPowerLeftJoinsMin',
            $orderByMinReturnType,
            [
                'string $column',
                'string|null $order'
            ]
        );

        $orderByMaxReturnType =
        Builder::class . '<static>';

        $command->setMethod(
            'orderByPowerJoinsMax',
            $orderByMaxReturnType,
            [
                'string $column',
                'string|null $order'
            ]
        );

        $command->setMethod(
            'orderByPowerLeftJoinsMax',
            $orderByMaxReturnType,
            [
                'string $column',
                'string|null $order'
            ]
        );

    }
}
