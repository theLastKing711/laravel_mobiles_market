<?php

namespace Tests\Feature\Admin\Abstractions;

use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class AdminTestCase extends TestCase
{
    use RefreshDatabase;

    private string $main_route = '/admin/users';

    public User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        //        parent::withHeader('Accept', 'application/json');

        $this->seed(RolesAndPermissionsSeeder::class);

        $this->CreateAdmin();

        $this->actingAs($this->admin);
    }

    private function CreateAdmin(): void
    {
        $this->admin =
            User::factory()
                ->staticAdmin()
                ->create();
    }

    public function getShowRoute(string $main_route, int $id)
    {
        return $main_route.'/'.$id;
    }

    /** @var Collection<string> description */
    public function genereateQueryParameters(Collection $query_parameters, string $query_parameter_name = 'ids')
    {

        $query_parameter_name_with_brackets = $query_parameter_name.'[]';

        $query_parameters = $query_parameters
            ->reduce(
                function ($prev, $curr, $index) use ($query_parameter_name_with_brackets) {
                    if ($index === 0) {
                        return $prev.'?'.$query_parameter_name_with_brackets.'='.$curr;
                    }

                    return $prev.'&'.$query_parameter_name_with_brackets.'='.$curr;
                },
                ''
            );

        return $query_parameters;

    }

    // public function post($uri, $data = [], $headers = []): TestResponse
    // {
    //     return
    //         parent::withHeaders(['Accept' => 'application/json'])
    //             ->post($uri, $data, $headers);

    // }
}
