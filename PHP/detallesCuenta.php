<?php
session_start();
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

    <main>
        <?php if (isset($_SESSION['usuario'])): ?>
            <h1>Tu cuenta</h1>
            <p>Aquí puedes administrar tu información y cerrar sesión.</p>
            <form action="logout.php" method="post">
                <button type="submit" class="btn-logout">Cerrar Sesión</button>
            </form>
        <?php else: ?>
            <h1>Necesitas iniciar sesión para acceder a tu cuenta</h1>
            <a href="login.php" class="btn-ir-login">Iniciar Sesión</a>
        <?php endif; ?>
    </main>
</body>
</html>
