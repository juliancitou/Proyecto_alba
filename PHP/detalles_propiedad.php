<?php
require_once 'base_de_datos.php';

$id_propiedad = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id_propiedad > 0) {
    $stmt = $pdo->prepare("SELECT * FROM propiedades WHERE id_propiedad = ?");
    $stmt->execute([$id_propiedad]);
    $propiedad = $stmt->fetch();

    if (!$propiedad) {
        echo "Propiedad no encontrada.";
        exit;
    }

    // Obtener imágenes reales desde la tabla imagenes_propiedad
    $stmt_imgs = $pdo->prepare("SELECT ruta_imagen FROM imagenes_propiedad WHERE id_propiedad = ?");
    $stmt_imgs->execute([$id_propiedad]);
    $imagenes_crudas = $stmt_imgs->fetchAll(PDO::FETCH_COLUMN);

    // Anteponer "../" a cada imagen como en index.php
    $imagenes = [];
    foreach ($imagenes_crudas as $img) {
        $imagenes[] = "../" . $img;
    }

    // Si no hay imágenes, usar una imagen por defecto
    if (empty($imagenes)) {
        $imagenes = ['../imagenes/imagen_defecto.jpg'];
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
    <link rel="stylesheet" href="../CSS/estilo_detalles_prpiedad.css">
</head>
<body>

    <div class="encabezado">
        <img src="../imagenes/logo_empresa.jpg" alt="Logo de la empresa">
        <h1>Detalles de la Propiedad</h1>
    </div>

    <a href="../PHP/index.php" class="btn-atras">← Atrás</a>

    <div class="contenido">
        <div class="galeria">
            <div style="position: relative;">
                <span class="navegacion izquierda" onclick="cambiarImagen(-1)">❮</span>
                <img id="imagen-principal" src="<?= htmlspecialchars($imagenes[0]) ?>" alt="Imagen principal">
                <span class="navegacion derecha" onclick="cambiarImagen(1)">❯</span>
            </div>
            <div class="miniaturas">
                <?php foreach ($imagenes as $index => $img): ?>
                    <img src="<?= htmlspecialchars($img) ?>" onclick="mostrarImagen(<?= $index ?>)" id="miniatura<?= $index ?>">
                <?php endforeach; ?>
            </div>
        </div>

        <div class="detalles">
            <h2><?= htmlspecialchars($propiedad['direccion']) ?> (<?= htmlspecialchars($propiedad['tipo_propiedad']) ?>)</h2>
            <p><strong>Descripción:</strong> <?= htmlspecialchars($propiedad['descripcion']) ?></p>
            <p><strong>Precio:</strong> $<?= number_format($propiedad['precio'], 2) ?></p>
            <p><strong>Estado:</strong> <?= $propiedad['estado'] === 'vendido' ? 'Vendido' : 'Disponible' ?></p>
            <p><strong>Metros Cuadrados:</strong> <?= htmlspecialchars($propiedad['metros_cuadrados']) ?> m²</p>
            <p><strong>Ubicación:</strong> <?= htmlspecialchars($propiedad['pais'] . ', ' . $propiedad['estado_propiedad'] . ', ' . $propiedad['municipio']) ?></p>

            <button class="btn-comprar"; href="https://api.whatsapp.com/send?phone=4811036018">Contactar</button>
        </div>
    </div>

    <script>
        const imagenes = <?= json_encode($imagenes) ?>;
        let indiceActual = 0;

        function mostrarImagen(indice) {
            document.getElementById("imagen-principal").src = imagenes[indice];
            document.querySelectorAll('.miniaturas img').forEach(img => img.classList.remove('seleccionada'));
            document.getElementById("miniatura" + indice).classList.add('seleccionada');
            indiceActual = indice;
        }

        function cambiarImagen(direccion) {
            indiceActual = (indiceActual + direccion + imagenes.length) % imagenes.length;
            mostrarImagen(indiceActual);
        }

        mostrarImagen(0);
    </script>

</body>
</html>
