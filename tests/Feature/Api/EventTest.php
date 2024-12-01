<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventTest extends TestCase
{
    use RefreshDatabase;

    public function test_events_endpoint_is_protected()
    {
        // Test without authentication
        $response = $this->getJson('/api/events');
        $response->assertStatus(401); // Should be unauthorized

        // Test with authentication
        $user = User::factory()->create();
        $response = $this->actingAs($user)
            ->getJson('/api/events');
        $response->assertStatus(200); // Should be successful
    }
}