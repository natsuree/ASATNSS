<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Notification;
use App\Models\User;
use App\Services\EmailValidationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class WebhookController extends Controller
{
    public function __construct(private readonly EmailValidationService $emailValidationService)
    {
    }

    public function tally(Request $request): JsonResponse
    {
        if (! $this->hasValidSecret($request)) {
            Log::warning('Rejected Tally webhook with invalid secret.');

            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $payload = $request->all();
        $submissionId = $this->extractSubmissionId($payload);

        if ($submissionId && Application::where('tally_submission_id', $submissionId)->exists()) {
            return response()->json(['message' => 'Duplicate submission ignored'], 200);
        }

        $applicationData = $this->extractApplicationData($payload, $submissionId);

        $validator = Validator::make($applicationData, [
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'student_id' => ['required', 'string', 'max:50'],
            'course' => ['required', 'string', 'max:255'],
            'year_level' => ['required', 'string', 'max:50'],
            'scholarship_type' => ['required', 'string', 'max:255'],
            'reason_for_applying' => ['nullable', 'string', 'max:5000'],
            'tally_submission_id' => ['nullable', 'string', 'max:255', 'unique:applications,tally_submission_id'],
        ]);

        if ($validator->fails()) {
            Log::warning('Rejected Tally webhook with invalid application data.', [
                'errors' => $validator->errors()->toArray(),
                'payload' => $payload,
            ]);

            return response()->json(['message' => 'Invalid application data'], 422);
        }

        $emailValidation = $this->emailValidationService->validate($applicationData['email']);

        if (! $emailValidation['valid']) {
            Log::warning('Rejected Tally webhook with invalid email.', [
                'email' => $applicationData['email'],
                'reason' => $emailValidation['reason'],
            ]);

            return response()->json(['message' => $emailValidation['reason']], 422);
        }

        $application = DB::transaction(function () use ($applicationData) {
            $user = User::firstOrCreate(
                ['email' => $applicationData['email']],
                [
                    'name' => $applicationData['full_name'],
                    'password' => Hash::make(Str::random(32)),
                ]
            );

            $application = $user->applications()->create($applicationData);

            Notification::create([
                'user_id' => $user->id,
                'title' => 'Tally application received',
                'message' => 'Your '.$application->scholarship_type.' scholarship application was received through Tally.',
                'status' => 'Unread',
                'is_read' => false,
            ]);

            return $application;
        });

        return response()->json([
            'message' => 'Application saved',
            'application_id' => $application->id,
        ], 201);
    }

    private function hasValidSecret(Request $request): bool
    {
        $secret = config('services.tally.webhook_secret');

        if (blank($secret)) {
            Log::warning('Tally webhook secret is not configured; rejecting request.');

            return false;
        }

        $headerSecret = $request->header('X-Tally-Webhook-Secret');
        $bearerSecret = Str::after($request->header('Authorization', ''), 'Bearer ');

        return hash_equals($secret, $headerSecret ?? '') || hash_equals($secret, $bearerSecret);
    }

    private function extractSubmissionId(array $payload): ?string
    {
        return data_get($payload, 'data.submissionId')
            ?? data_get($payload, 'data.id')
            ?? data_get($payload, 'submissionId')
            ?? data_get($payload, 'id');
    }

    private function extractApplicationData(array $payload, ?string $submissionId): array
    {
        $fields = collect(data_get($payload, 'data.fields', []))
            ->mapWithKeys(function (array $field) {
                $label = Str::of(data_get($field, 'label') ?? data_get($field, 'key') ?? data_get($field, 'name') ?? '')
                    ->lower()
                    ->replace([' ', '-', '.'], '_')
                    ->toString();

                $value = data_get($field, 'value');

                if (is_array($value)) {
                    $value = implode(', ', $value);
                }

                return [$label => $value];
            });

        return [
            'full_name' => data_get($payload, 'full_name') ?? data_get($payload, 'data.full_name') ?? $fields->get('full_name') ?? $fields->get('name'),
            'email' => data_get($payload, 'email') ?? data_get($payload, 'data.email') ?? $fields->get('email'),
            'student_id' => data_get($payload, 'student_id') ?? data_get($payload, 'data.student_id') ?? $fields->get('student_id'),
            'course' => data_get($payload, 'course') ?? data_get($payload, 'data.course') ?? $fields->get('course'),
            'year_level' => data_get($payload, 'year_level') ?? data_get($payload, 'data.year_level') ?? $fields->get('year_level'),
            'scholarship_type' => data_get($payload, 'scholarship_type') ?? data_get($payload, 'data.scholarship_type') ?? $fields->get('scholarship_type'),
            'reason_for_applying' => data_get($payload, 'reason_for_applying') ?? data_get($payload, 'data.reason_for_applying') ?? $fields->get('reason_for_applying'),
            'tally_submission_id' => $submissionId,
        ];
    }
}
