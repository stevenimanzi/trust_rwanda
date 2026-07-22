<?php

namespace App\Services;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

class MtnMomoService
{
    public function requestToPay(string $externalId, string $phone, float $amount): string
    {
        $reference = (string) Str::uuid();
        $headers = $this->headers($reference);

        if (config('mtn_momo.callback_enabled')) {
            $headers['X-Callback-Url'] = route('mtn-momo.callback', ['reference' => $reference]);
        }

        $response = Http::withHeaders($headers)
            ->withToken($this->accessToken())
            ->post($this->url('/collection/v1_0/requesttopay'), [
                'amount' => number_format($amount, 2, '.', ''),
                'currency' => config('mtn_momo.currency'),
                'externalId' => $externalId,
                'payer' => [
                    'partyIdType' => 'MSISDN',
                    'partyId' => $this->normalizeRwandaPhone($phone),
                ],
                'payerMessage' => "Trust Rwanda order {$externalId}",
                'payeeNote' => "Payment for {$externalId}",
            ]);

        if ($response->status() !== 202) {
            throw $this->apiException('MTN MoMo rejected the payment request', $response);
        }

        return $reference;
    }

    public function paymentStatus(string $reference): array
    {
        $response = Http::withHeaders($this->headers())
            ->withToken($this->accessToken())
            ->get($this->url("/collection/v1_0/requesttopay/{$reference}"));

        if (!$response->successful()) {
            throw $this->apiException('Unable to verify the MTN MoMo payment', $response);
        }

        return $response->json();
    }

    public function normalizeRwandaPhone(string $phone): string
    {
        $digits = preg_replace('/\D+/', '', $phone);
        if (str_starts_with($digits, '250')) {
            $digits = substr($digits, 3);
        }
        if (str_starts_with($digits, '0')) {
            $digits = substr($digits, 1);
        }
        if (!preg_match('/^7\d{8}$/', $digits)) {
            throw new RuntimeException('Enter a valid Rwanda MTN number, for example 078XXXXXXX.');
        }

        return '250'.$digits;
    }

    private function accessToken(): string
    {
        $this->assertConfigured();

        return Cache::remember('mtn_momo_collection_token', 3300, function () {
            $response = Http::withBasicAuth(config('mtn_momo.api_user'), config('mtn_momo.api_key'))
                ->withHeaders(['Ocp-Apim-Subscription-Key' => config('mtn_momo.subscription_key')])
                ->post($this->url('/collection/token/'));

            if (!$response->successful() || !$response->json('access_token')) {
                throw $this->apiException('Unable to authenticate with MTN MoMo', $response);
            }

            return $response->json('access_token');
        });
    }

    private function headers(?string $reference = null): array
    {
        $headers = [
            'Ocp-Apim-Subscription-Key' => config('mtn_momo.subscription_key'),
            'X-Target-Environment' => config('mtn_momo.target_environment'),
            'Content-Type' => 'application/json',
        ];
        if ($reference) {
            $headers['X-Reference-Id'] = $reference;
        }

        return $headers;
    }

    private function assertConfigured(): void
    {
        foreach (['api_user', 'api_key', 'subscription_key'] as $key) {
            if (!config("mtn_momo.{$key}")) {
                throw new RuntimeException('MTN MoMo is not configured. Add the API credentials to the environment.');
            }
        }
    }

    private function url(string $path): string
    {
        return rtrim(config('mtn_momo.base_url'), '/').$path;
    }

    private function apiException(string $message, Response $response): RuntimeException
    {
        $detail = $response->json('message') ?: $response->json('reason') ?: $response->body();
        return new RuntimeException($message.($detail ? ': '.$detail : '.'));
    }
}
