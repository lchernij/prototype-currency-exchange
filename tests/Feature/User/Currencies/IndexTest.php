<?php

namespace Tests\Feature\Users\Currencies;

use App\Models\User;
use App\Models\UserCurrency;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    public function testIsProtectedRoute(): void
    {
        $this->getJson('api/user/currencies')
            ->assertStatus(401)
            ->assertExactJson(
                [
                    "message" => "Unauthenticated."
                ]
            );
    }

    public function testEmptyIndex(): void
    {
        $this->mockUserAuth();

        $this->getJson('api/user/currencies')
            ->assertStatus(200)
            ->assertExactJson(
                [
                    'count' => 0,
                    'data' => [],
                    'next' => null,
                    'prev' => null,
                    'total' => 0,
                ]
            );
    }

    public function testIndex(): void
    {
        $this->mockUserAuth();
        $this->mockCurrencies();
        $currency = UserCurrency::first();

        $this->getJson('api/user/currencies')
            ->assertStatus(200)
            ->assertExactJson(
                [
                    'count' => 1,
                    'data' => [
                        [
                            'uuid' => $currency->uuid,
                            'value_trigger' => $currency->value_trigger,
                            'value_action' => $currency->value_action,
                            'currency' => [
                                'uuid' => $currency->currency->uuid,
                                'symbol' => $currency->currency->symbol,
                                'description' => $currency->currency->description,
                            ]
                        ]
                    ],
                    'next' => null,
                    'prev' => null,
                    'total' => 1,
                ]
            );
    }

    public function testIndexPagination(): void
    {
        $this->mockUserAuth();
        $this->mockCurrencies(11);

        $this->getJson('api/user/currencies?page=2')
            ->assertStatus(200)
            ->assertJsonFragment(
                [
                    'count' => 5,
                    'next' => 'http://localhost/api/user/currencies?page=3',
                    'prev' => 'http://localhost/api/user/currencies?page=1',
                    'total' => 11,
                ]
            );
    }

    private function mockUserAuth(): void
    {
        $this->user = User::factory()->create();

        $this->be($this->user);
    }

    private function mockCurrencies(int $count = 1): void
    {
        UserCurrency::factory()->count($count)->create([
            'user_id' => $this->user->id
        ]);
    }
}
