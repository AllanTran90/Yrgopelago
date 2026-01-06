<?php
declare(strict_types=1);
require_once __DIR__ . '/../vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

// protect from dubbleloading
if (function_exists('validateTransferCode')) {
    return;
}

// validations of transfer code
function validateTransferCode(string $transferCode, int $totalCost): bool
{
    $client = new Client([
        'base_uri' => 'http://www.yrgopelag.se',
        'timeout'  => 3,
    ]);

    try {
        $response = $client->post('/centralbank/transferCode', [
            'form_params' => [
                'transferCode' => $transferCode,
                'totalCost'    => $totalCost,
            ],
        ]);

        $data = json_decode($response->getBody()->getContents(), true);

        return isset($data['status']) && $data['status'] === 'success';

    } catch (\Throwable $e) {
        error_log('Centralbank validation failed: ' . $e->getMessage());
        return false;
    }
}

// deposit money to hotel owner
function depositMoney(string $user, string $apiKey, string $transferCode): bool
{
    $client = new Client([
        'base_uri' => 'http://www.yrgopelag.se',
        'timeout'  => 5,
    ]);

    try {
        $response = $client->post('/centralbank/deposit', [
            'json' => [
                'user'         => $user,
                'api_key'      => $apiKey,
                'transferCode' => $transferCode,
            ],
        ]);

        $data = json_decode($response->getBody()->getContents(), true);

        return isset($data['status']) && $data['status'] === 'success';

    } catch (\Throwable $e) {
        error_log('Centralbank deposit failed: ' . $e->getMessage());
        return false;
    }
}
