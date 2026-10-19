<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_admin_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
    }

    public function test_anggota_forbidden_from_admin_dashboard(): void
    {
        $anggota = User::factory()->create(['role' => 'anggota']);

        $response = $this->actingAs($anggota)->get(route('admin.dashboard'));

        $response->assertStatus(403);
    }

    public function test_anggota_can_access_anggota_dashboard(): void
    {
        $anggota = User::factory()->create(['role' => 'anggota']);

        $response = $this->actingAs($anggota)->get(route('anggota.dashboard'));

        $response->assertStatus(200);
    }

    public function test_admin_forbidden_from_anggota_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('anggota.dashboard'));

        $response->assertStatus(403);
    }

    public function test_dashboard_route_redirects_based_on_role(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $anggota = User::factory()->create(['role' => 'anggota']);

        $this->actingAs($admin)->get(route('dashboard'))->assertRedirect(route('admin.dashboard'));
        $this->actingAs($anggota)->get(route('dashboard'))->assertRedirect(route('anggota.dashboard'));
    }
}
