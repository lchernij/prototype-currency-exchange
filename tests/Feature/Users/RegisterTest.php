<?php

namespace Tests\Feature\Users;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @dataProvider provideInvalidInputs
     */
    public function testValidForm($payload, $expected): void
    {
        $this->postJson('api/register', $payload)
            ->assertStatus(422)
            ->assertExactJson($expected);
    }

    public function testValidFormUnique(): void
    {
        User::factory()->create([
            "email" => "foo2@bar.com"
        ]);

        $payload = [
            "name" => "foo bar",
            "email" => "foo2@bar.com",
            "password" => "123456",
            "confirm_password" => "123456"
        ];

        $this->postJson('api/register', $payload)
            ->assertStatus(422)
            ->assertExactJson([
                'errors' => [
                    'email' => [
                        'The email has already been taken.',
                    ],
                ],
                'message' => 'The given data was invalid.',
            ]);
    }

    public function testRegister(): void
    {
        $payload = [
            "name" => "foo bar",
            "email" => "foo@bar.com",
            "password" => "123456",
            "confirm_password" => "123456"
        ];

        $response = $this->postJson('api/register', $payload);

        $response->assertStatus(201)
            ->assertJsonStructure([
                "name",
                "email",
                "uuid",
                "token"
            ]);

        $this->assertEquals($payload['name'], $response['name']);
        $this->assertEquals($payload['email'], $response['email']);
        
        $this->assertNotNull($response['uuid']);
        $this->assertNotNull($response['token']);
    }

    /**
     * Provider from testValidForm
     */
    public function provideInvalidInputs(): array
    {
        $maxLenghtPassword = Str::random(129);

        return [
            "required" => [
                "payload" => [],
                "expected" => [
                    'errors' => [
                        'confirm_password' => [
                            'The confirm password field is required.',
                        ],
                        'email' => [
                            'The email field is required.',
                        ],
                        'name' => [
                            'The name field is required.',
                        ],
                        'password' => [
                            'The password field is required.',
                        ],
                    ],
                    'message' => 'The given data was invalid.',
                ]
            ],
            "min" => [
                "payload" => [
                    "name" => "f",
                    "email" => "foo@bar.com",
                    "password" => "12345",
                    "confirm_password" => "12345"
                ],
                "expected" => [
                    'errors' => [
                        'name' => [
                            'The name must be at least 2 characters.',
                        ],
                        'password' => [
                            'The password must be at least 6 characters.',
                        ],
                    ],
                    'message' => 'The given data was invalid.',
                ]
            ],
            "max" => [
                "payload" => [
                    "name" => Str::random(129),
                    "email" => "foo@bar.com",
                    "password" => $maxLenghtPassword,
                    "confirm_password" => $maxLenghtPassword
                ],
                "expected" => [
                    'errors' => [
                        'name' => [
                            'The name must not be greater than 128 characters.',
                        ],
                        'password' => [
                            'The password must not be greater than 128 characters.',
                        ],
                    ],
                    'message' => 'The given data was invalid.',
                ]
            ],
            "email" => [
                "payload" => [
                    "name" => "foo bar",
                    "email" => "foobar",
                    "password" => "123456",
                    "confirm_password" => "123456"
                ],
                "expected" => [
                    'errors' => [
                        'email' => [
                            'The email must be a valid email address.',
                        ],
                    ],
                    'message' => 'The given data was invalid.',
                ]
            ],
            "same" => [
                "payload" => [
                    "name" => "foo bar",
                    "email" => "foo@bar.com",
                    "password" => "1234567",
                    "confirm_password" => "123456"
                ],
                "expected" => [
                    'errors' => [
                        'confirm_password' => [
                            'The confirm password and password must match.',
                        ],
                    ],
                    'message' => 'The given data was invalid.',
                ]
            ]
        ];
    }
}
