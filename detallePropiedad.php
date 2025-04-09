<?php
session_start();
include_once "base_de_datos.php";

if (!isset($_GET['id'])) {
    header("Location: bienvenida.php");
    exit();
}

$idPropiedad = $_GET['id'];

// Obtener detalles de la propiedad
$sql = "SELECT p.*, e.nombre AS empresa_nombre, e.rfc AS empresa_rfc FROM propiedades p
        LEFT JOIN empresas e ON p.id_empresa = e.rfc
        WHERE p.id_propiedad = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $idPropiedad]);
$propiedad = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$propiedad) {
    echo "Propiedad no encontrada.";
    exit();
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle de la Propiedad</title>
</head>
<body>
    <h1>Detalle de la Propiedad</h1>

    <div style="display: flex; gap: 20px;">
        <!-- Carrusel de imágenes -->
        <div style="flex: 1; border: 1px solid #ccc; padding: 10px;">
            <h2>Imágenes</h2>
            <!-- Aquí puedes implementar un carrusel con JavaScript -->
            <div id="carrusel">
                <p>[Carrusel de imágenes aquí]</p>
            </div>
        </div>

        <!-- Detalles de la propiedad -->
        <div style="flex: 1; border: 1px solid #ccc; padding: 10px;">
            <p><strong>Tipo:</strong> <?php echo htmlspecialchars($propiedad['tipo']); ?></p>
            <p><strong>Municipio:</strong> <?php echo htmlspecialchars($propiedad['municipio']); ?></p>
            <p><strong>Estado:</strong> <?php echo htmlspecialchars($propiedad['estado']); ?></p>
            <p><strong>Tamaño:</strong> <?php echo htmlspecialchars($propiedad['tamaño']); ?> m²</p>
            <p><strong>Precio:</strong> $<?php echo htmlspecialchars($propiedad['precio']); ?></p>
            <p><strong>Empresa:</strong> <?php echo htmlspecialchars($propiedad['empresa_nombre']); ?></p>

            <!-- Botón Comprar -->
            <form method="POST" action="comprarPropiedad.php">
                <input type="hidden" name="id_propiedad" value="<?php echo htmlspecialchars($propiedad['id_propiedad']); ?>">
                <input type="hidden" name="precio" value="<?php echo htmlspecialchars($propiedad['precio']); ?>">
                <input type="hidden" name="empresa_rfc" value="<?php echo htmlspecialchars($propiedad['empresa_rfc']); ?>">
                <button type="submit">Comprar</button>
            </form>
        </div>
    </div>
</body>
</html>
