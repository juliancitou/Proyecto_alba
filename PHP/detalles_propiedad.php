<?php
require_once 'base_de_datos.php';

// Obtener el ID de la propiedad desde la URL
$id_propiedad = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id_propiedad > 0) {
    // Consultar la propiedad en la base de datos
    $stmt = $pdo->prepare("SELECT * FROM propiedades WHERE id_propiedad = ?");
    $stmt->execute([$id_propiedad]);
    $propiedad = $stmt->fetch();

    if (!$propiedad) {
        echo "Propiedad no encontrada.";
        exit;
    }
} else {
    echo "ID de propiedad no válido.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalles de la Propiedad</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <header>
        <h1>Detalles de la Propiedad</h1>
    </header>

    <div class="propiedad-detalle">
        <h3><?= htmlspecialchars($propiedad['tipo_propiedad']) ?> - <?= htmlspecialchars($propiedad['direccion']) ?></h3>
        <p><strong>Descripción:</strong> <?= htmlspecialchars($propiedad['descripcion']) ?></p>
        <p><strong>Precio:</strong> $<?= number_format($propiedad['precio'], 2) ?></p>
        <p><strong>Estado:</strong> <?= ($propiedad['estado'] == 'vendido') ? 'Vendido' : 'Disponible' ?></p>
        <p><strong>Metros Cuadrados:</strong> <?= htmlspecialchars($propiedad['metros_cuadrados']) ?> m²</p>
        <p><strong>País:</strong> <?= htmlspecialchars($propiedad['pais']) ?></p>
        <p><strong>Estado:</strong> <?= htmlspecialchars($propiedad['estado']) ?></p>
        <p><strong>Municipio:</strong> <?= htmlspecialchars($propiedad['municipio']) ?></p>

        <!-- Botón para regresar a la página principal -->
        <a href="index.php" class="btn btn-primary">Regresar</a>
    </div>
</body>
</html>
