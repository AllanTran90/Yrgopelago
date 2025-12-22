<?php
declare(strict_types=1);

try {
     
    $pdo = new PDO('sqlite:database/yrgopelago.db');
    $pdo->exec('PRAGMA foreign_keys = ON');
    $pdo ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}

catch(PDOException $error){
   die("Database error: " . $error->getMessage());
}
