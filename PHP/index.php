<?php
session_start();
require_once 'base_de_datos.php'; // Conexión usando PDO

// Si el usuario está logueado, actualizamos su información con el rol real desde la base
if (isset($_SESSION['id_usuario'])) {
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id_usuario = ?");
    $stmt->execute([$_SESSION['id_usuario']]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        $_SESSION['usuario'] = $usuario;
        $_SESSION['rol'] = $usuario['rol'];
    }
}

// Obtener propiedades (limitadas a 3)
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

    <?php if (isset($_SESSION['usuario'])): ?>
        <?php
        $rolTexto = ($_SESSION['tipo'] === "usuario") ? $_SESSION['usuario']['rol'] : "empresa";
        $idTexto = $_SESSION['tipo'] === "usuario" ? $_SESSION['usuario']['id_usuario'] : $_SESSION['usuario']['rfc'];
        ?>
        <div class="user-info" style="margin: 20px; padding: 10px; background-color: #f5f5f5; border: 1px solid #ccc;">
            <strong>Sesión iniciada:</strong><br>
            Rol: <b><?= htmlspecialchars($rolTexto) ?></b><br>
            ID: <b><?= htmlspecialchars($idTexto) ?></b><br>
            Nombre: <b><?= htmlspecialchars($_SESSION['usuario']['nombre']) ?></b>
        </div>
    <?php endif; ?>

</head>

<body>
    <header>
        <div class="d-flex align-items-center">
            <img src="../imagenes/logo_empresa.jpg" alt="Logo" class="logo-img">
            <h2>InmueblesFáciles</h2>
        </div>

        <?php if (!isset($_SESSION['usuario'])): ?>
            <a href="login.php"><button class="btn-login">Iniciar Sesión</button></a>
        <?php else: ?>
            <div class="ms-auto me-3 d-flex align-items-center">
                <span>Hola, <?= htmlspecialchars($_SESSION['usuario']['nombre'] . ' ' . $_SESSION['usuario']['apellidos']) ?></span>
                <a href="logout.php" class="btn btn-sm btn-outline-secondary ms-3">Cerrar sesión</a>
            </div>
        <?php endif; ?>
    </header>

    <div class="menu-container">
        <div id="menu-navegacion">
            <nav class="nav-separado">
                <a href="index.php">Inicio</a>
                <a href="vender.php">Vender</a>
                <a href="panel_vendedor.php">Propiedades</a>
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
                $stmtImg = $pdo->prepare("SELECT ruta_imagen FROM imagenes_propiedad WHERE id_propiedad = ? LIMIT 1");
                $stmtImg->execute([$propiedad['id_propiedad']]);
                $imagen = $stmtImg->fetchColumn();
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