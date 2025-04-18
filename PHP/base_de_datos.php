<?php
$config = include("config.php");

try {
    $pdo = new PDO(
        "pgsql:host={$config["host"]};dbname={$config["dbname"]}",
        $config["user"],
        $config["password"]
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>
