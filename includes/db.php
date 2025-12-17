<?php
declare(strict_types=1);

try {
    $pdo = new PDO('/database/yrgopelago.db');
    $pdo ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}

catch(PDOException $error){
   die("Database error: " . $error->getMessage());
}
