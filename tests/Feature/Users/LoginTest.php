<?php

namespace Tests\Feature\Users;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @dataProvider provideInvalidInputs
     */
    public function testValidForm($payload, $expected): void
    {
        $this->postJson('api/login', $payload)
            ->assertStatus(422)
            ->assertExactJson($expected);
    }

    public function testInvalidLogin(): void
    {
        $payload = [
            "email" => "foo@bar.com",
            "password" => "123456"
        ];

        $this->postJson('api/login', $payload)
            ->assertStatus(401)
            ->assertExactJson(["unauthorized"]);
    }

    public function testValidLogin(): void
    {
        $user = User::factory()->create([
            "email" => "foo@bar.com",
            "password" => bcrypt("123456")
        ]);
        
        $payload = [
            "email" => "foo@bar.com",
            "password" => "123456"
        ];
        
        $response = $this->postJson('api/login', $payload);

        $response->assertStatus(200)
            ->assertJsonStructure([
                "name",
                "email",
                "uuid",
                "token"
            ]);

        $this->assertEquals($user->name, $response['name']);
        $this->assertEquals($user->email, $response['email']);
        
        $this->assertNotNull($response['uuid']);
        $this->assertNotNull($response['token']);
    }

    /**
     * Provider from testValidForm
     */
    public function provideInvalidInputs(): array
    {
        return [
            "required" => [
                "payload" => [],
                "expected" => [
                    'errors' => [
                        'email' => [
                            'The email field is required.',
                        ],
                        'password' => [
                            'The password field is required.',
                        ],
                    ],
                    'message' => 'The given data was invalid.',
                ]
            ]
        ];
    }
}
