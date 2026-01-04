<?php
declare(strict_types=1);

// protect from dubbleloading
if (function_exists('validateTransferCode')) {
    return;
}

// validations of transfer code
function validateTransferCode(string $transferCode, int $totalCost): bool
{
    $url = 'http://www.yrgopelag.se/centralbank/transferCode';

    $postdata = [
        'transferCode' => $transferCode,
        'totalCost'    => $totalCost,
    ];

    $options = [
        'http' => [
            'method'  => 'POST',
            'header'  => "Content-Type: application/x-www-form-urlencoded\r\n",
            'content' => http_build_query($postdata),
            'timeout' => 5
        ],
    ];

    $context  = stream_context_create($options);
    $response = file_get_contents($url, false, $context);

    if ($response === false) {
        return false;
    }

    $result = json_decode($response, true);
    return isset($result['status']) && $result['status'] === 'success';
}

// deposit money to hotel owner
function depositMoney(string $user, string $apiKey, string $transferCode): bool
{
    $url = 'http://www.yrgopelag.se/centralbank/deposit';

    $data = [
        'user'         => $user,
        'api_key'      => $apiKey,
        'transferCode' => $transferCode,
    ];

    $options = [
        'http' => [
            'method'  => 'POST',
            'header'  => "Content-Type: application/json\r\n",
            'content' => json_encode($data),
            'timeout' => 5,
        ],
    ];

    $context  = stream_context_create($options);
    $response = @file_get_contents($url, false, $context);

    if ($response === false) {
        return false;
    }

    $result = json_decode($response, true);
    return isset($result['status']) && $result['status'] === 'success';
}
