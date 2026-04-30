<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminUserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_user_seeder_creates_default_admin_account(): void
    {
        $this->seed(AdminUserSeeder::class);

        $admin = User::where('email', 'admin@example.com')->first();

        $this->assertNotNull($admin);
        $this->assertSame('System Admin', $admin->name);
        $this->assertTrue($admin->is_admin);
        $this->assertTrue(Hash::check('password', $admin->password));
    }

    public function test_admin_can_view_user_management_page(): void
    {
        $admin = User::factory()->admin()->create();
        User::factory()->create(['name' => 'Student User']);

        $this->actingAs($admin)
            ->get('/admin/users')
            ->assertOk()
            ->assertSee('Student User')
            ->assertSee('Admin Users');
    }

    public function test_normal_user_is_redirected_from_user_management_page(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/admin/users')
            ->assertRedirect('/dashboard');
    }

    public function test_admin_can_promote_and_demote_other_users(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $this->actingAs($admin)
            ->patch("/admin/users/{$user->id}/promote")
            ->assertRedirect('/admin/users');

        $this->assertTrue($user->refresh()->is_admin);

        $this->actingAs($admin)
            ->patch("/admin/users/{$user->id}/demote")
            ->assertRedirect('/admin/users');

        $this->assertFalse($user->refresh()->is_admin);
    }

    public function test_admin_cannot_demote_themselves(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->patch("/admin/users/{$admin->id}/demote")
            ->assertRedirect('/admin/users')
            ->assertSessionHas('status', 'self-demotion-blocked');

        $this->assertTrue($admin->refresh()->is_admin);
    }
}
