<?php session_start();
require_once 'base_de_datos.php'; // Incluye la conexión a la base de datos


// Realiza la consulta a la base de datos para obtener las propiedades
$stmt = $pdo->prepare("SELECT * FROM propiedades");
$stmt->execute();
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
        <!-- Este es el menú oficial, abajo del título -->
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
        <!-- Tarjetas de propiedades -->
        <div class="container">

            <!-- Propiedad 1 -->
            <main>
                <!-- Tarjetas de propiedades -->
                <div class="container">
                    <?php
                    // Limitamos el número de propiedades a mostrar (3 en este caso)
                    $stmt = $pdo->query("SELECT * FROM propiedades LIMIT 3");
                    $propiedades = $stmt->fetchAll();

                    foreach ($propiedades as $propiedad): ?>
                        <div class="propiedad-card row" id="propiedad_<?= $propiedad['id_propiedad'] ?>">
                            <!-- Columna para la imagen de la propiedad -->
                            <div class="col-md-4 propiedad-imagen">
                                <img src="ruta_a_la_imagen/<?= htmlspecialchars($propiedad['imagen']) ?>" alt="Imagen de la propiedad" class="img-fluid">
                            </div>
                            <!-- Columna para la descripción de la propiedad y el botón -->
                            <div class="col-md-8 propiedad-detalles">
                                <h3><?= htmlspecialchars($propiedad['tipo_propiedad']) ?> - <?= htmlspecialchars($propiedad['direccion']) ?></h3>
                                <p><strong>Descripción:</strong> <?= htmlspecialchars($propiedad['descripcion']) ?></p>
                                <p><strong>Precio:</strong> $<?= number_format($propiedad['precio'], 2) ?></p>
                                <p><strong>Estado:</strong> <?= ($propiedad['estado'] == 'vendido') ? 'Vendido' : 'Disponible' ?></p>
                                <a href="detalles_propiedad.php?id=<?= $propiedad['id_propiedad'] ?>" class="btn-detalles">Detalles</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </main>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
</body>

</html>