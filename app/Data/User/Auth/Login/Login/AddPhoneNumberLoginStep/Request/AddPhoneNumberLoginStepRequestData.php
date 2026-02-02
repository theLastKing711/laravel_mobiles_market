<?php

namespace App\Data\User\Auth\Login\Login\AddPhoneNumberLoginStep\Request;

use App\Models\User;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[Oat\Schema()]
class AddPhoneNumberLoginStepRequestData extends Data
{
    public static function messages(...$args): array
    {
        return [
            'phone_number.unique' => __(
                'messages.users.auth.registeration.add-phone-number-step.phone_number.unique'
            ),
        ];
    }

    public function __construct(
        #[
            OAT\Property,
            Unique(User::class, 'phone_number')
        ]
        public string $phone_number,
    ) {}
}
