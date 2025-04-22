<?php 
session_start();
require_once 'base_de_datos.php'; // Incluye la conexión a la base de datos

// Obtener propiedades (limitadas a 3 como en tu código)
$stmt = $pdo->query("SELECT * FROM propiedades LIMIT 3");
$propiedades = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Bienvenido a InmueblesFáciles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../CSS/estilos.css">
</head>
<body>
    <header>
        <div class="d-flex align-items-center">
            <img src="../imagenes/logo_empresa.jpg" alt="Logo" class="logo-img">
            <h2>InmueblesFáciles</h2>
        </div>
        <?php if (!isset($_SESSION['usuario'])): ?>
            <a href="login.php"><button class="btn-login">Iniciar Sesión</button></a>
        <?php endif; ?>
    </header>

    <div class="menu-container">
        <div id="menu-navegacion">
            <nav class="nav-separado">
                <a href="index.php">Inicio</a>
                <a href="vender.php">Vender</a>
                <a href="ultima_visita.php">Seguir Comprando</a>
                <a href="detallesCuenta.php">Cuenta</a>
                <a href="menu.php">Menú</a>
            </nav>
        </div>
    </div>

    <div class="container-fluid mt-3" style="padding-left: 15%; padding-right: 15%;">
        <h1>Bienvenido</h1>
        <p>Explora terrenos y viviendas a la venta o renta de manera sencilla.</p>
    </div>

    <main>
        <div class="container">
            <?php foreach ($propiedades as $propiedad): 
                // Obtener UNA imagen de la propiedad desde la tabla imagenes_propiedad
                $stmtImg = $pdo->prepare("SELECT ruta_imagen FROM imagenes_propiedad WHERE id_propiedad = ? LIMIT 1");
                $stmtImg->execute([$propiedad['id_propiedad']]);
                $imagen = $stmtImg->fetchColumn();

                // Si no hay imagen, usar una imagen por defecto
                $ruta_imagen = $imagen ? "../$imagen" : "../imagenes/default.jpg";
            ?>
                <div class="propiedad-card row mb-4" id="propiedad_<?= $propiedad['id_propiedad'] ?>">
                    <div class="col-md-4 propiedad-imagen">
                        <img src="<?= htmlspecialchars($ruta_imagen) ?>" alt="Imagen de la propiedad" class="img-fluid rounded shadow">
                    </div>
                    <div class="col-md-8">
                        <h4><?= htmlspecialchars(ucfirst($propiedad['tipo_propiedad'])) ?></h4>
                        <p><strong>Dirección:</strong> <?= htmlspecialchars($propiedad['direccion']) ?></p>
                        <p><strong>Precio:</strong> $<?= number_format($propiedad['precio'], 2) ?></p>
                        <a href="detalles_propiedad.php?id=<?= $propiedad['id_propiedad'] ?>" class="btn btn-primary">Ver más</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
</body>
</html>
