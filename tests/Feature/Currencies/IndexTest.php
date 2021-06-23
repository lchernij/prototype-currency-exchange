<?php

namespace Tests\Feature\Currencies;

use App\Models\Currency;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    public function testIsProtectedRoute(): void
    {
        $this->getJson('api/currencies')
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

        $this->getJson('api/currencies')
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
        $currency = Currency::first();

        $this->getJson('api/currencies')
            ->assertStatus(200)
            ->assertExactJson(
                [
                    'count' => 1,
                    'data' => [
                        [
                            'created_at' => $currency->created_at,
                            'deleted_at' => NULL,
                            'description' => $currency->description,
                            'symbol' => $currency->symbol,
                            'updated_at' => $currency->updated_at,
                            'uuid' => $currency->uuid,
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

        $this->getJson('api/currencies?page=2')
            ->assertStatus(200)
            ->assertJsonFragment(
                [
                    'count' => 5,
                    'next' => 'http://localhost/api/currencies?page=3',
                    'prev' => 'http://localhost/api/currencies?page=1',
                    'total' => 11,
                ]
            );
    }

    private function mockUserAuth(): void
    {
        $user = User::factory()->create();

        $this->be($user);
    }

    private function mockCurrencies(int $count = 1): void
    {
        Currency::factory()->count($count)->create();
    }
}
