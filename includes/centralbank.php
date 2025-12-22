<?php
declare(strict_types=1);

function validateTransferCode(string $transferCode, int $totalCost): bool{
    return true;
}

function chargeCentralBank (int $amount): bool{
    $url = 'https://www.yrgopelag.se/centralbank/?amount=' .$amount;

    $response = file_get_contents($url);

    if($response === false){
        return false;
    }
    return trim($response) ==='OK';
}

function depositMoney(string $transferCode): bool{
    return true;
}