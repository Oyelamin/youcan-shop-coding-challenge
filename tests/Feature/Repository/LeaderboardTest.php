<?php

namespace Tests\Feature\Repository;

use App\Models\User;
use App\Services\GitHubApiService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\AttachJwtToken;
use Tests\TestCase;

class LeaderboardTest extends TestCase
{
    use AttachJwtToken, WithFaker;
    private User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /**
     * A basic feature test example.
     */
    public function test_can_show_leaderboard(): void
    {
        $mockedService = $this->mock(GitHubApiService::class);
        $expectedResponse = [];
        $mockedService->shouldReceive('getRepositoryPullRequests')
            ->once()->andReturn($expectedResponse);
        $mockedService->shouldReceive('getRepositoryPullRequestReviews')
            ->andReturn($expectedResponse);
        $response = $this->actingAs($this->user)->getJson(route('api.repositories.leaderboard.index', [
                'repository' => $this->faker->slug
            ])
        )->assertSuccessful()
            ->assertJsonStructure([
                'message',
                'status',
                'data',
                'meta',
            ]);

        $response->assertStatus(200);
    }
}
