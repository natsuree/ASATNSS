<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Notification;
use App\Models\Profile;
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

    public function test_admin_can_remove_a_user_and_related_records_safely(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $application = Application::create([
            'user_id' => $user->id,
            'full_name' => 'Student User',
            'email' => $user->email,
            'student_id' => '2026-0001',
            'course' => 'BSIT',
            'year_level' => '3',
            'scholarship_type' => 'Academic',
            'reason_for_applying' => 'Need support',
            'status' => Application::STATUS_PENDING,
        ]);

        $profile = Profile::create([
            'user_id' => $user->id,
            'phone' => '09123456789',
        ]);

        $notification = Notification::create([
            'user_id' => $user->id,
            'title' => 'Hello',
            'message' => 'World',
            'status' => 'Unread',
            'is_read' => false,
        ]);

        $this->actingAs($admin)
            ->delete("/admin/users/{$user->id}")
            ->assertRedirect('/admin/users')
            ->assertSessionHas('status', 'user-removed');

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->assertDatabaseMissing('applications', ['id' => $application->id]);
        $this->assertDatabaseMissing('profiles', ['id' => $profile->id]);
        $this->assertDatabaseMissing('notifications', ['id' => $notification->id]);
    }

    public function test_admin_cannot_delete_themselves(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->delete("/admin/users/{$admin->id}")
            ->assertRedirect('/admin/users')
            ->assertSessionHas('status', 'self-delete-blocked');

        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_admin_can_remove_another_admin_when_more_than_one_admin_exists(): void
    {
        $primaryAdmin = User::factory()->admin()->create();
        $secondaryAdmin = User::factory()->admin()->create();

        $this->actingAs($primaryAdmin)
            ->delete("/admin/users/{$secondaryAdmin->id}")
            ->assertRedirect('/admin/users')
            ->assertSessionHas('status', 'user-removed');

        $this->assertDatabaseMissing('users', ['id' => $secondaryAdmin->id]);
        $this->assertDatabaseHas('users', ['id' => $primaryAdmin->id, 'is_admin' => true]);
    }

    public function test_normal_user_is_redirected_from_user_delete_route(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $this->actingAs($user)
            ->delete("/admin/users/{$admin->id}")
            ->assertRedirect('/dashboard');

        $this->assertDatabaseHas('users', ['id' => $admin->id, 'is_admin' => true]);
    }
}
