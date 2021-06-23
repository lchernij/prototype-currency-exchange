<?php

namespace Tests\Feature\User\Currencies;

use App\Models\User;
use App\Models\UserCurrency;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use RefreshDatabase;

    public function testIsProtectedRoute(): void
    {
        $this->getJson('api/user/currencies/6cc43b06-2eef-48c5-9a0e-0124a269406f')
            ->assertStatus(401)
            ->assertExactJson(
                [
                    "message" => "Unauthenticated."
                ]
            );
    }

    public function testNotFound(): void
    {
        $this->mockUserAuth();

        $this->getJson('api/user/currencies/6cc43b06-2eef-48c5-9a0e-0124a269406f')
            ->assertStatus(404)
            ->assertJsonFragment(
                [
                    'message' => 'No query results for model [App\\Models\\UserCurrency].',
                ]
            );
    }

    public function testShow(): void
    {
        $this->mockUserAuth();
        $currency = UserCurrency::factory()->create([
            'user_id' => $this->user->id
        ]);

        $this->getJson('api/user/currencies/' . $currency->uuid)
            ->assertStatus(200)
            ->assertExactJson(
                [
                    'data' => [
                        'uuid' => $currency->uuid,
                        'value_trigger' => $currency->value_trigger,
                        'value_action' => $currency->value_action,
                        'currency' => [
                            'uuid' => $currency->currency->uuid,
                            'symbol' => $currency->currency->symbol,
                            'description' => $currency->currency->description,
                        ]
                    ]
                ]
            );
    }

    private function mockUserAuth(): void
    {
        $this->user = User::factory()->create();

        $this->be($this->user);
    }
}
