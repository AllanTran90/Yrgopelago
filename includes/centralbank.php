<?php
declare(strict_types=1);

const CENTRALBANK_USER = 'Allan';
const CENTRALBANK_API_KEY = 'input api key here';


function validateTransferCode(string $transferCode, int $totalCost): bool{
    $url = 'http://www.yrgopelag.se/centralbank/transferCode';

    $data = [
        'transferCode' => $transferCode,
        'totalCost' => $totalCost,
    ];
}

function depositMoney(string $transferCode): bool{
    return true;
}
