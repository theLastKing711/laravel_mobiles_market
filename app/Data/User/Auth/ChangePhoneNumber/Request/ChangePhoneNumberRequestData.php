<?php

namespace App\Data\User\Auth\ChangePhoneNumber\Request;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Attributes\MergeValidationRules;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[MergeValidationRules]
#[Oat\Schema()]
class ChangePhoneNumberRequestData extends Data
{
    public function __construct(
        #[OAT\Property]
        public string $phone_number,
    ) {}

    public static function rules(?ValidationContext $context = null): array
    {
        return [
            'phone_number' => [
                Rule::unique(
                    User::class,
                    'phone_number'
                )
                    ->ignore(Auth::id()),
            ],
        ];
    }

    public static function messages(...$args): array
    {
        return [
            'phone_number.unique' => __(
                'messages.users.auth.account.change-phone-number.phone_number.unique'
            ),
        ];
    }
}
