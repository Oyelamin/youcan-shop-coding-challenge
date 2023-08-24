<?php

namespace Tests\Feature\Auth;

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
        $mockedService = $this->mock(GitHubApiService::class);

        // Set expectations for the mocked service
        $expectedProfile = ['login' => $this->faker->text];
        $mockedService->shouldReceive('userDetailResult');
        $mockedService->shouldReceive('getUsername');
        $mockedService->shouldReceive('getUserProfile')
            ->once()->andReturn($mockedService);
        $mockedService->shouldReceive('userDetailResult')
            ->andReturn($expectedProfile);
        $mockedService->shouldReceive('getUsername')
            ->andReturn($this->faker->text);

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
