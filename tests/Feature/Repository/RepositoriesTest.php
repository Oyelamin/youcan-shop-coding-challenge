<?php

namespace Tests\Feature\Repository;

use App\Models\User;
use App\Services\GitHubApiService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\AttachJwtToken;
use Tests\TestCase;

class RepositoriesTest extends TestCase
{
    use AttachJwtToken, WithFaker;
    private User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /**
     * A test to list user repositories
     */
    public function test_can_list_repositories(): void
    {
        $mockedService = $this->mock(GitHubApiService::class);
        $expectedResponse = [];
        $mockedService->shouldReceive('getUserRepositories')
            ->once()->andReturn($expectedResponse);
        $response = $this->actingAs($this->user)->getJson(route('api.repositories.index'))
            ->assertSuccessful()
            ->assertJsonStructure([
                'message',
                'status',
                'data',
                'meta',
            ]);

        $response->assertStatus(200);
    }

    public function test_can_show_repository_details(){
        $mockedService = $this->mock(GitHubApiService::class);
        $expectedResponse = [];
        $mockedService->shouldReceive('getUserRepository')
            ->once()->andReturn($expectedResponse);
        $response = $this->actingAs($this->user)->getJson(
            route('api.repositories.show', [
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
