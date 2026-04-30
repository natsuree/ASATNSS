<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class EmailValidationService
{
    public function validate(string $email): array
    {
        $apiKey = config('services.abstract.email_reputation_key');

        if (blank($apiKey)) {
            return [
                'valid' => true,
                'reason' => 'Email validation temporarily unavailable.',
            ];
        }

        try {
            $response = Http::timeout(5)->acceptJson()->get('https://emailreputation.abstractapi.com/v1/', [
                'api_key' => $apiKey,
                'email' => $email,
            ]);

            if ($response->failed()) {
                Log::warning('Abstract Email Reputation API request failed.', [
                    'email' => $email,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return [
                    'valid' => true,
                    'reason' => 'Email validation temporarily unavailable.',
                ];
            }

            $data = $response->json();
            $qualityScore = data_get($data, 'email_quality.score');
            $isValidSyntax = data_get($data, 'email_deliverability.is_format_valid') === true;
            $isDisposable = data_get($data, 'email_quality.is_disposable') === true;
            $isUsernameSuspicious = data_get($data, 'email_quality.is_username_suspicious') === true;
            $isRiskyTld = data_get($data, 'email_domain.is_risky_tld') === true;
            $deliverabilityStatus = data_get($data, 'email_deliverability.status');
            $addressRiskStatus = data_get($data, 'email_risk.address_risk_status');
            $domainRiskStatus = data_get($data, 'email_risk.domain_risk_status');

            $isSuspicious = ! $isValidSyntax
                || $isDisposable
                || $isUsernameSuspicious
                || $isRiskyTld
                || $deliverabilityStatus === 'undeliverable'
                || $addressRiskStatus === 'high'
                || $domainRiskStatus === 'high'
                || $qualityScore === null
                || (float) $qualityScore < 0.5;

            if ($isSuspicious) {
                Log::warning('Suspicious email rejected by Abstract Email Reputation API.', [
                    'email' => $email,
                    'deliverability_status' => $deliverabilityStatus,
                    'quality_score' => $qualityScore,
                    'address_risk_status' => $addressRiskStatus,
                    'domain_risk_status' => $domainRiskStatus,
                    'is_disposable' => $isDisposable,
                    'is_username_suspicious' => $isUsernameSuspicious,
                    'is_risky_tld' => $isRiskyTld,
                ]);

                return [
                    'valid' => false,
                    'reason' => 'The email address could not be verified.',
                ];
            }

            return [
                'valid' => true,
                'reason' => 'Email address verified.',
            ];
        } catch (Throwable $exception) {
            Log::warning('Abstract Email Reputation API exception.', [
                'email' => $email,
                'message' => $exception->getMessage(),
            ]);

            return [
                'valid' => true,
                'reason' => 'Email validation temporarily unavailable.',
            ];
        }
    }
}
