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

        if (config('app.debug')) {
            Log::debug('Mapped Tally webhook payload.', [
                'submission_id' => $submissionId,
                'mapped_payload' => $applicationData,
            ]);
        }

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
                'mapped_payload' => $applicationData,
            ]);

            $response = ['message' => 'Invalid application data'];

            if (config('app.debug')) {
                $response['errors'] = $validator->errors()->toArray();
                $response['mapped_payload'] = $applicationData;
            }

            return response()->json($response, 422);
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
        ], 200);
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
        $querySecret = $request->query('secret') ?? $request->query('tally_secret');
        $payloadSecret = $request->input('secret') ?? $request->input('tally_secret') ?? $request->input('webhook_secret');

        return hash_equals($secret, $headerSecret ?? '')
            || hash_equals($secret, $bearerSecret)
            || hash_equals($secret, $querySecret ?? '')
            || hash_equals($secret, $payloadSecret ?? '');
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
                $label = data_get($field, 'label') ?? data_get($field, 'key') ?? data_get($field, 'name') ?? '';
                $normalizedLabel = $this->normalizeFieldLabel($label);
                $value = $this->extractFieldValue($field);

                return filled($normalizedLabel) ? [$normalizedLabel => $value] : [];
            });

        return [
            'full_name' => data_get($payload, 'full_name') ?? data_get($payload, 'data.full_name') ?? $this->firstMatchingField($fields, ['full_name', 'name', 'student_name', 'applicant_name']),
            'email' => data_get($payload, 'email') ?? data_get($payload, 'data.email') ?? $fields->get('email'),
            'student_id' => data_get($payload, 'student_id') ?? data_get($payload, 'data.student_id') ?? $this->firstMatchingField($fields, ['student_id', 'student_no', 'student_number']),
            'course' => data_get($payload, 'course') ?? data_get($payload, 'data.course') ?? $fields->get('course'),
            'year_level' => data_get($payload, 'year_level') ?? data_get($payload, 'data.year_level') ?? $this->firstMatchingField($fields, ['year_level', 'year_lever', 'year']),
            'scholarship_type' => data_get($payload, 'scholarship_type') ?? data_get($payload, 'data.scholarship_type') ?? $this->firstMatchingField($fields, ['scholarship_type', 'scholarship']),
            'reason_for_applying' => data_get($payload, 'reason_for_applying') ?? data_get($payload, 'data.reason_for_applying') ?? $this->firstMatchingField($fields, ['reason_for_applying', 'reason', 'why_are_you_applying']),
            'tally_submission_id' => $submissionId,
        ];
    }

    private function extractFieldValue(array $field): mixed
    {
        $rawValue = data_get($field, 'value');
        $resolvedOptionValue = $this->resolveFieldOptionValue($rawValue, $field);

        if (filled($resolvedOptionValue)) {
            return $resolvedOptionValue;
        }

        $value = $this->normalizeFieldValue($rawValue);

        if (filled($value)) {
            return $value;
        }

        foreach (['selectedOptions', 'choices', 'answer'] as $key) {
            $fallbackValue = $this->normalizeFieldValue(data_get($field, $key));

            if (filled($fallbackValue)) {
                return $fallbackValue;
            }
        }

        return null;
    }

    private function resolveFieldOptionValue(mixed $rawValue, array $field): ?string
    {
        $options = collect(data_get($field, 'options', []))
            ->mapWithKeys(function ($option) {
                $optionId = data_get($option, 'id') ?? data_get($option, 'value');
                $optionText = data_get($option, 'text') ?? data_get($option, 'label') ?? data_get($option, 'name');

                if (! filled($optionId) || ! filled($optionText)) {
                    return [];
                }

                return [(string) $optionId => trim((string) $optionText)];
            });

        if ($options->isEmpty()) {
            return null;
        }

        $selectedOptionIds = $this->extractOptionIds($rawValue);

        if ($selectedOptionIds === []) {
            return null;
        }

        $resolvedLabels = collect($selectedOptionIds)
            ->map(fn ($optionId) => $options->get($optionId))
            ->filter(fn ($label) => filled($label))
            ->unique()
            ->values();

        return $resolvedLabels->isNotEmpty() ? $resolvedLabels->implode(', ') : null;
    }

    private function extractOptionIds(mixed $value): array
    {
        if ($value === null) {
            return [];
        }

        if (is_scalar($value)) {
            $normalized = trim((string) $value);

            return $normalized !== '' ? [$normalized] : [];
        }

        if (! is_array($value)) {
            $normalized = trim((string) $value);

            return $normalized !== '' ? [$normalized] : [];
        }

        return collect($value)
            ->flatMap(function ($item) {
                if (is_scalar($item)) {
                    $normalized = trim((string) $item);

                    return $normalized !== '' ? [$normalized] : [];
                }

                if (is_array($item)) {
                    foreach (['id', 'value'] as $key) {
                        $candidate = data_get($item, $key);

                        if (is_scalar($candidate) && trim((string) $candidate) !== '') {
                            return [trim((string) $candidate)];
                        }
                    }
                }

                return $this->extractOptionIds($item);
            })
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    private function normalizeFieldValue(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        if (is_bool($value)) {
            return $value ? 'Yes' : 'No';
        }

        if (is_scalar($value)) {
            $normalized = trim((string) $value);

            return $normalized !== '' ? $normalized : null;
        }

        if (! is_array($value)) {
            $normalized = trim((string) $value);

            return $normalized !== '' ? $normalized : null;
        }

        $fullName = trim(implode(' ', array_filter([
            data_get($value, 'first_name') ?? data_get($value, 'firstName'),
            data_get($value, 'last_name') ?? data_get($value, 'lastName'),
        ])));

        if ($fullName !== '') {
            return $fullName;
        }

        foreach (['text', 'label', 'name', 'email'] as $candidateKey) {
            $candidate = data_get($value, $candidateKey);

            if (is_scalar($candidate) && trim((string) $candidate) !== '') {
                return trim((string) $candidate);
            }
        }

        if (array_key_exists('value', $value)) {
            $candidate = $this->normalizeFieldValue($value['value']);

            if (filled($candidate)) {
                return $candidate;
            }
        }

        $items = collect($value)
            ->map(function ($item, $key) {
                if (in_array($key, ['id', 'ids'], true)) {
                    return null;
                }

                return $this->normalizeFieldValue($item);
            })
            ->filter(fn ($item) => filled($item))
            ->unique()
            ->values();

        return $items->isNotEmpty() ? $items->implode(', ') : null;
    }

    private function normalizeFieldLabel(string $label): string
    {
        return Str::of($label)
            ->lower()
            ->replaceMatches('/[^a-z0-9]+/', '_')
            ->trim('_')
            ->toString();
    }

    private function firstMatchingField($fields, array $keys): mixed
    {
        foreach ($keys as $key) {
            if ($fields->has($key)) {
                return $fields->get($key);
            }
        }

        foreach ($fields as $key => $value) {
            foreach ($keys as $expectedKey) {
                if (str_contains($key, $expectedKey)) {
                    return $value;
                }
            }
        }

        return null;
    }
}
