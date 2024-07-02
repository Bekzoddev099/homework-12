<?php
$dsn = 'mysql:host=localhost;dbname=work_off_tracker';
$username = 'beko';
$password = '9999';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
?>
