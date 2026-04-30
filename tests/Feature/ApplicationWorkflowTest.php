<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ApplicationWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['services.abstract.email_reputation_key' => null]);
    }

    public function test_student_can_submit_multiple_scholarship_applications(): void
    {
        $user = User::factory()->create();

        $payload = [
            'full_name' => 'Juan Dela Cruz',
            'email' => 'juan@example.com',
            'student_id' => 'S-1001',
            'course' => 'BS Information Technology',
            'year_level' => '3rd Year',
            'scholarship_type' => 'Academic',
            'reason_for_applying' => 'Consistent academic performance.',
        ];

        $this->actingAs($user)->post('/applications', $payload)
            ->assertSessionHasNoErrors()
            ->assertRedirect('/applications');

        $this->actingAs($user)->post('/applications', [
            ...$payload,
            'scholarship_type' => 'Leadership',
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseCount('applications', 2);
        $this->assertDatabaseHas('notifications', [
            'user_id' => $user->id,
            'title' => 'Application submitted',
            'is_read' => false,
        ]);
    }

    public function test_admin_access_is_role_protected(): void
    {
        $student = User::factory()->create();
        $admin = User::factory()->admin()->create();

        $this->actingAs($student)->get('/admin/applications')->assertRedirect('/dashboard');
        $this->actingAs($admin)->get('/admin/applications')->assertOk();
    }

    public function test_admin_can_update_application_status_and_notify_student(): void
    {
        $student = User::factory()->create();
        $admin = User::factory()->admin()->create();
        $application = Application::create([
            'user_id' => $student->id,
            'full_name' => 'Maria Santos',
            'email' => 'maria@example.com',
            'student_id' => 'S-1002',
            'course' => 'BS Education',
            'year_level' => '4th Year',
            'scholarship_type' => 'Financial Assistance',
            'status' => Application::STATUS_PENDING,
        ]);

        $this->actingAs($admin)
            ->patch("/admin/applications/{$application->id}", ['status' => Application::STATUS_APPROVED])
            ->assertSessionHasNoErrors()
            ->assertRedirect('/admin/applications');

        $this->assertDatabaseHas('applications', [
            'id' => $application->id,
            'status' => Application::STATUS_APPROVED,
        ]);

        $this->assertDatabaseHas('notifications', [
            'user_id' => $student->id,
            'title' => 'Application Approved',
        ]);
    }

    public function test_pending_student_application_appears_on_admin_dashboard(): void
    {
        $student = User::factory()->create();
        $admin = User::factory()->admin()->create();

        Application::create([
            'user_id' => $student->id,
            'full_name' => 'Geo Clark Calasag',
            'email' => 'geo@example.com',
            'student_id' => 'S-2002',
            'course' => 'BS Information Technology',
            'year_level' => '4th Year',
            'scholarship_type' => 'Academic',
            'status' => Application::STATUS_PENDING,
        ]);

        $this->actingAs($admin)
            ->get('/admin/applications')
            ->assertOk()
            ->assertSee('Geo Clark Calasag')
            ->assertSee('Pending')
            ->assertSee('Approve')
            ->assertSee('Reject');
    }

    public function test_tally_webhook_saves_application_and_prevents_duplicates(): void
    {
        config(['services.tally.webhook_secret' => 'secret-token']);

        $payload = [
            'data' => [
                'submissionId' => 'tally-123',
                'fields' => [
                    ['label' => 'Full Name', 'value' => 'Ana Reyes'],
                    ['label' => 'Email', 'value' => 'ana@example.com'],
                    ['label' => 'Student ID', 'value' => 'S-1003'],
                    ['label' => 'Course', 'value' => 'BS Accountancy'],
                    ['label' => 'Year Level', 'value' => '2nd Year'],
                    ['label' => 'Scholarship Type', 'value' => 'Academic'],
                    ['label' => 'Reason For Applying', 'value' => 'Needs support for tuition.'],
                ],
            ],
        ];

        $this->withHeader('X-Tally-Webhook-Secret', 'secret-token')
            ->postJson('/api/webhooks/tally', $payload)
            ->assertCreated();

        $this->withHeader('X-Tally-Webhook-Secret', 'secret-token')
            ->postJson('/api/webhooks/tally', $payload)
            ->assertOk()
            ->assertJson(['message' => 'Duplicate submission ignored']);

        $this->assertDatabaseCount('applications', 1);
        $this->assertDatabaseHas('applications', [
            'email' => 'ana@example.com',
            'tally_submission_id' => 'tally-123',
        ]);
    }

    public function test_notifications_can_be_marked_as_read(): void
    {
        $user = User::factory()->create();
        $notification = Notification::create([
            'user_id' => $user->id,
            'title' => 'Status update',
            'message' => 'Your application was updated.',
        ]);

        $this->actingAs($user)->patch("/notifications/{$notification->id}")->assertRedirect();

        $this->assertDatabaseHas('notifications', [
            'id' => $notification->id,
            'status' => 'Read',
            'is_read' => true,
        ]);
    }

    public function test_validate_email_endpoint_uses_abstract_email_reputation_api(): void
    {
        config(['services.abstract.email_reputation_key' => 'test-key']);

        Http::fake([
            'https://emailreputation.abstractapi.com/v1/*' => Http::response([
                'email_address' => 'test@example.com',
                'email_deliverability' => [
                    'status' => 'deliverable',
                    'status_detail' => 'valid_email',
                    'is_format_valid' => true,
                ],
                'email_quality' => [
                    'score' => 0.8,
                    'is_username_suspicious' => false,
                    'is_disposable' => false,
                ],
                'email_domain' => [
                    'is_risky_tld' => false,
                ],
                'email_risk' => [
                    'address_risk_status' => 'low',
                    'domain_risk_status' => 'low',
                ],
            ]),
        ]);

        $this->postJson('/api/validate-email', ['email' => 'test@example.com'])
            ->assertOk()
            ->assertExactJson([
                'valid' => true,
                'reason' => 'Email address verified.',
            ]);

        Http::assertSent(function ($request) {
            parse_str(parse_url($request->url(), PHP_URL_QUERY) ?? '', $query);

            return str_starts_with($request->url(), 'https://emailreputation.abstractapi.com/v1/')
                && ($query['api_key'] ?? null) === 'test-key'
                && ($query['email'] ?? null) === 'test@example.com';
        });
    }

    public function test_validate_email_endpoint_rejects_suspicious_reputation(): void
    {
        config(['services.abstract.email_reputation_key' => 'test-key']);

        Http::fake([
            'https://emailreputation.abstractapi.com/v1/*' => Http::response([
                'email_address' => 'risky@example.test',
                'email_deliverability' => [
                    'status' => 'undeliverable',
                    'status_detail' => 'invalid_mailbox',
                    'is_format_valid' => true,
                ],
                'email_quality' => [
                    'score' => 0.2,
                    'is_username_suspicious' => true,
                    'is_disposable' => true,
                ],
                'email_domain' => [
                    'is_risky_tld' => true,
                ],
                'email_risk' => [
                    'address_risk_status' => 'high',
                    'domain_risk_status' => 'high',
                ],
            ]),
        ]);

        $this->postJson('/api/validate-email', ['email' => 'risky@example.test'])
            ->assertOk()
            ->assertExactJson([
                'valid' => false,
                'reason' => 'The email address could not be verified.',
            ]);
    }

    public function test_validate_email_endpoint_falls_back_when_reputation_api_fails(): void
    {
        config(['services.abstract.email_reputation_key' => 'test-key']);

        Http::fake([
            'https://emailreputation.abstractapi.com/v1/*' => Http::response(['error' => 'quota reached'], 422),
        ]);

        $this->postJson('/api/validate-email', ['email' => 'test@example.com'])
            ->assertOk()
            ->assertExactJson([
                'valid' => true,
                'reason' => 'Email validation temporarily unavailable.',
            ]);
    }
}
