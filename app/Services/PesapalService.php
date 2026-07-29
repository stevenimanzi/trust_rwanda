<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\PendingRequest;

class PesapalService
{
    protected $consumerKey;
    protected $consumerSecret;
    protected $baseUrl;

    public function __construct()
    {
        $this->consumerKey = config('pesapal.consumer_key');
        $this->consumerSecret = config('pesapal.consumer_secret');
        $env = config('pesapal.env');
        $this->baseUrl = $env === 'production' ? config('pesapal.live_api') : config('pesapal.sandbox_api');
    }

    public function authenticate()
    {
        if (!$this->consumerKey || !$this->consumerSecret) {
            throw new \RuntimeException('Pesapal credentials are not configured.');
        }

        $response = $this->client()->post("{$this->baseUrl}/api/Auth/RequestToken", [
            'consumer_key' => $this->consumerKey,
            'consumer_secret' => $this->consumerSecret,
        ]);

        if ($response->successful() && $response->json('status') == '200') {
            return $response->json('token');
        }

        Log::error('Pesapal Auth Failed', ['response' => $response->body()]);
        throw new \Exception('Failed to authenticate with Pesapal.');
    }

    public function registerIPN($token, $ipnUrl)
    {
        $response = $this->client()->withToken($token)->post("{$this->baseUrl}/api/URLSetup/RegisterIPN", [
            'url' => $ipnUrl,
            'ipn_notification_type' => 'POST',
        ]);

        if ($response->successful() && $response->json('status') == '200') {
            return $response->json('ipn_id');
        }

        Log::error('Pesapal IPN Registration Failed', ['response' => $response->body()]);
        throw new \Exception('Failed to register Pesapal IPN.');
    }

    public function submitOrder($token, $orderData)
    {
        $response = $this->client()->withToken($token)->post("{$this->baseUrl}/api/Transactions/SubmitOrderRequest", $orderData);

        if ($response->successful() && $response->json('status') == '200') {
            return $response->json();
        }

        $errorMsg = 'Failed to submit order to Pesapal.';
        $errorData = $response->json();
        if (isset($errorData['error']['message'])) {
            $errorMsg = $errorData['error']['message'];
        }

        Log::error('Pesapal Submit Order Failed', ['response' => $response->body()]);
        throw new \Exception($errorMsg);
    }

    public function getTransactionStatus($token, $orderTrackingId)
    {
        $response = $this->client()->withToken($token)->get("{$this->baseUrl}/api/Transactions/GetTransactionStatus", [
            'orderTrackingId' => $orderTrackingId,
        ]);

        if ($response->successful() && $response->json('status') == '200') {
            return $response->json();
        }

        Log::error('Pesapal Get Status Failed', ['response' => $response->body()]);
        throw new \Exception('Failed to get Pesapal transaction status.');
    }

    private function client(): PendingRequest
    {
        $client = Http::timeout(20);

        if (defined('CURLSSLOPT_NATIVE_CA')) {
            $client = $client->withOptions([
                'curl' => [CURLOPT_SSL_OPTIONS => CURLSSLOPT_NATIVE_CA],
            ]);
        }

        return $client;
    }
}
