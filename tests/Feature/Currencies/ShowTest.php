<?php

namespace Tests\Feature\Currencies;

use App\Models\Currency;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use RefreshDatabase;

    public function testIsProtectedRoute(): void
    {
        $this->getJson('api/currencies/6cc43b06-2eef-48c5-9a0e-0124a269406f')
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

        $this->getJson('api/currencies/6cc43b06-2eef-48c5-9a0e-0124a269406f')
            ->assertStatus(404)
            ->assertJsonFragment(
                [
                    'message' => 'No query results for model [App\\Models\\Currency].',
                ]
            );
    }

    public function testShow(): void
    {
        $this->mockUserAuth();
        $currency = Currency::factory()->create();

        $this->getJson('api/currencies/' . $currency->uuid)
            ->assertStatus(200)
            ->assertExactJson(
                [
                    'data' => [
                        'created_at' => $currency->created_at,
                        'deleted_at' => NULL,
                        'description' => $currency->description,
                        'symbol' => $currency->symbol,
                        'updated_at' => $currency->updated_at,
                        'uuid' => $currency->uuid,
                    ]
                ]
            );
    }

    private function mockUserAuth(): void
    {
        $user = User::factory()->create();

        $this->be($user);
    }
}
