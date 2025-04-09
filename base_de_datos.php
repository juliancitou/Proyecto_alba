<?php
$config = include "config.php";

try {
    $dsn = "pgsql:host={$config['host']};dbname={$config['dbname']}";
    $pdo = new PDO($dsn, $config["user"], $config["password"]);

    // Configuración de errores
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Conexión exitosa (solo para depuración; en producción evita imprimir mensajes de conexión).
} catch (PDOException $e) {
    die("Error al conectar a la base de datos: " . $e->getMessage());
}
?>
