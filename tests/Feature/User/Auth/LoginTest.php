<?php

namespace Tests\Feature\User\Auth;

use App\Data\User\Auth\Login\AddPhoneNumberLoginStep\Request\AddPhoneNumberLoginStepRequestData;
use App\Data\User\Auth\Login\Login\Request\LoginRequestData;
use App\Models\User;
use Cloudinary\Api\HttpStatusCode;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\User\Abstractions\UserTestCase;

class LoginTest extends UserTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->withRoutePaths(
                'auth',
                'login'
            );

    }

    #[Test]
    public function enter_phone_number_in_login_phone_step_success_with_200_response(): void
    {

        $this
            ->withRoutePaths(
                'phone-number-step'
            );

        // $logged_in_user = $this
        //     ->getUser();

        // $this->actingAs(
        //     $logged_in_user
        // );

        $new_user =
            User::factory()
                ->create();

        $registeration_step_request_data =
            new AddPhoneNumberLoginStepRequestData(
                phone_number: $new_user->phone_number
            );

        $response =
           $this
               ->postJsonData(
                   $registeration_step_request_data
                       ->toArray()
               );

        $response->assertStatus(200);

    }

    #[Test]
    public function enter_non_existing_phone_number_in_login_phone_step_errors_with_404_response(): void
    {

        $this
            ->withRoutePaths(
                'phone-number-step'
            );

        $login_phone_number_steprequest_data =
            new AddPhoneNumberLoginStepRequestData(
                phone_number: fake()->phoneNumber()
            );

        $response =
           $this
               ->postJsonData(
                   $login_phone_number_steprequest_data
                       ->toArray()
               );

        $response->assertStatus(404);

    }

    #[Test]
    public function login_success_with_200_response(): void
    {

        $this
            ->withRoutePaths(
                'login'
            );

        $new_user =
            User::factory()
                ->create();

        $login_request_data =
            new LoginRequestData(
                $new_user->phone_number,
                $new_user->password
            );

        $response =
           $this
               ->postJsonData(
                   $login_request_data
                       ->toArray()
               );

        $response->assertStatus(HttpStatusCode::UNAUTHORIZED);

    }

    /**
     * @param  callable(): LoginRequestData  $request
     **/
    #[
        Test,
        DataProvider('wrong_phonenumber_password_provider')
    ]
    public function login_with_wrong_username_errors_with_401_response($request): void
    {

        $this
            ->withRoutePaths(
                'login'
            );

        // $new_user =
        //     User::factory()
        //         ->create();

        $login_request_data =
            new LoginRequestData(
                $request()->phone_number,
                $request()->password
            );

        $response =
           $this
               ->postJsonData(
                   $login_request_data
                       ->toArray()
               );

        $response->assertStatus(HttpStatusCode::UNAUTHORIZED);

    }

    /**
     * @return array<callable(): LoginRequestData>
     **/
    public static function wrong_phonenumber_password_provider(): array
    {

        return [
            [function (): LoginRequestData {
                $user = User::factory()->create();

                return new LoginRequestData('wrong_phone_number', $user->password);
            }],
            [function (): LoginRequestData {
                $user = User::factory()->create();

                return new LoginRequestData($user->phone_number, 'wrong_password');
            }],
            [fn () => new LoginRequestData('wrong_username', 'wrong_password')],
        ];

    }
}
