<?php
declare(strict_types=1);

$database = new PDO('sqlite:database/yrgopelago.db');

try {
    $pdo = new PDO($database);
    $pdo ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}

catch(PDOException $error){
    echo "Database error";
    exit;
}
