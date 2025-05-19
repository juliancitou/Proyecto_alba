<?php
session_start();
include_once "base_de_datos.php";

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'vendedor') {
    header("Location: login.php");
    exit();
}

$id_propiedad = $_GET["id"] ?? null;
$id_vendedor = $_SESSION['usuario']['id_usuario'];

if ($id_propiedad) {
    // Eliminar imágenes
    $ruta = "imagenes_propiedades/$id_propiedad";
    if (is_dir($ruta)) {
        $archivos = array_diff(scandir($ruta), ['.', '..']);
        foreach ($archivos as $archivo) {
            unlink("$ruta/$archivo");
        }
        rmdir($ruta);
    }

    // Eliminar propiedad
    $stmt = $pdo->prepare("DELETE FROM propiedades WHERE id_propiedad = :id AND id_vendedor = :vendedor");
    $stmt->execute([':id' => $id_propiedad, ':vendedor' => $id_vendedor]);
}

header("Location: panel_vendedor.php");
exit();
