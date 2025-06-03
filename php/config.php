<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'natan');
define('DB_PASS', 'natan');
define('DB_NAME', 'sae23');

try {
    $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET CHARACTER SET utf8");
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

session_start();
?>
