<?php

namespace Tests\Feature\Auth;

use App\Helpers\AuthenticationsHelper;
use App\Services\GitHubApiService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserLoginTest extends TestCase
{
    use WithFaker;
    /**
     * A basic feature test example.
     */
    public function testGitHubLogin(): void
    {
        // Mock the GitHubApiService
        $mockedHelper = $this->mock(AuthenticationsHelper::class);

        // Set expectations for the mocked service
        $mockedHelper->shouldReceive('verifyToken')->andReturn(new GitHubApiService());
        $mockedHelper->shouldReceive('attemptLogin')->andReturn($this->faker->text);

        // Perform the API call
        $this
            ->postJson(route('api.auth.login'), [
                'oAuthToken'    => $this->faker->text
            ])->assertSuccessful()
            ->assertJsonStructure([
                'message',
                'status',
                'data' => [
                    'token',
                ],
                'meta',
            ]);
    }
}
