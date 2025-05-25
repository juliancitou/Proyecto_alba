<?php
session_start();
include 'base_de_datos.php';

// Validar y guardar foto de perfil
if (isset($_FILES['foto_perfil']) && isset($_POST['cambiar_foto'])) {
    $usuario = $_SESSION['usuario'];
    $idUsuario = $usuario['id_usuario'];

    $nombreArchivo = $_FILES['foto_perfil']['name'];
    $tipoArchivo = $_FILES['foto_perfil']['type'];
    $tamanoArchivo = $_FILES['foto_perfil']['size'];
    $archivoTmp = $_FILES['foto_perfil']['tmp_name'];

    $extensionesPermitidas = ['image/jpeg', 'image/png', 'image/gif'];
    $limiteTamano = 5 * 1024 * 1024; // 5MB

    if (in_array($tipoArchivo, $extensionesPermitidas) && $tamanoArchivo <= $limiteTamano) {
        $extension = pathinfo($nombreArchivo, PATHINFO_EXTENSION);
        $nuevoNombre = $idUsuario . '.' . $extension;
        $rutaDestino = '../fotos_perfil/' . $nuevoNombre;

        if (move_uploaded_file($archivoTmp, $rutaDestino)) {
            $stmt = $pdo->prepare("UPDATE usuarios SET foto_perfil = ? WHERE id_usuario = ?");
            $stmt->execute([$nuevoNombre, $idUsuario]);

            $_SESSION['usuario']['foto_perfil'] = $nuevoNombre;
            $_SESSION['mensaje_foto'] = "Foto de perfil actualizada correctamente.";
        } else {
            $_SESSION['error_foto'] = "Error al mover el archivo.";
        }
    } else {
        $_SESSION['error_foto'] = "Archivo no válido. Solo imágenes JPG, PNG o GIF menores a 5MB.";
    }

    header("Location: detallesCuenta.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Bienvenido a InmueblesFáciles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../CSS/estilos.css">
    <style>
        .perfil-container {
            max-width: 400px;
            margin: auto;
            padding: 20px;
        }
        .rounded-circle {
            object-fit: cover;
        }
    </style>
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
            <a href="panel_vendedor.php">Propiedades</a>
            <a href="detallesCuenta.php">Cuenta</a>
            <a href="menu.php">Menú</a>
        </nav>
    </div>
</div>

<main class="container mt-4">
    <?php if (isset($_SESSION['usuario'])):
        $usuario = $_SESSION['usuario'];

        // Contar propiedades en venta
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM propiedades WHERE id_vendedor = ?");
        $stmt->execute([$usuario['id_usuario']]);
        $totalPropiedades = $stmt->fetchColumn();

        // Obtener fecha de registro
        $stmtFecha = $pdo->prepare("SELECT fecha_registro FROM usuarios WHERE id_usuario = ?");
        $stmtFecha->execute([$usuario['id_usuario']]);
        $fechaRegistro = $stmtFecha->fetchColumn();

        $fotoPerfil = $usuario['foto_perfil'] ?? 'imagen_default.png';
        $rutaFoto = "../fotos_perfil/" . $fotoPerfil;
    ?>
        <h1 class="text-center">Tu Cuenta</h1>
        <p class="text-center">Aquí puedes administrar tu información y cerrar sesión.</p>

        <div class="perfil-container text-center">
            <img src="<?= $rutaFoto ?>" alt="Foto de perfil" class="rounded-circle mb-3" width="150" height="150">

            <form action="detallesCuenta.php" method="POST" enctype="multipart/form-data">
                <input type="file" name="foto_perfil" id="inputFoto" class="form-control" onchange="habilitarBoton()">
                <br>
                <button type="submit" name="cambiar_foto" class="btn btn-primary" id="botonCambiar" disabled>Cambiar Foto</button>
            </form>

            <?php if (isset($_SESSION['mensaje_foto'])): ?>
                <p class="text-success"><?= $_SESSION['mensaje_foto'] ?></p>
                <?php unset($_SESSION['mensaje_foto']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error_foto'])): ?>
                <p class="text-danger"><?= $_SESSION['error_foto'] ?></p>
                <?php unset($_SESSION['error_foto']); ?>
            <?php endif; ?>
        </div>

        <div class="mt-4 text-center">
            <h5><strong>Nombre:</strong> <?= $usuario['nombre'] . ' ' . $usuario['apellidos'] ?></h5>
            <p><strong>Correo:</strong> <?= $usuario['correo'] ?></p>
            <p><strong>Teléfono:</strong> <?= $usuario['telefono'] ?></p>
            <p><strong>CURP:</strong> <?= $usuario['curp'] ?></p>
            <p><strong>Edad:</strong> <?= $usuario['edad'] ?></p>
            <p><strong>Dirección:</strong> <?= $usuario['direccion'] ?></p>
            <p><strong>Propiedades publicadas:</strong> <?= $totalPropiedades ?></p>
            <p><strong>Registrado desde:</strong> <?= date('d/m/Y', strtotime($fechaRegistro)) ?></p>
        </div>

        <form action="logout.php" method="post" class="text-center mt-4">
            <button type="submit" class="btn btn-danger">Cerrar Sesión</button>
        </form>
    <?php else: ?>
        <h1 class="text-center">Necesitas iniciar sesión para acceder a tu cuenta</h1>
        <div class="text-center">
            <a href="login.php" class="btn btn-primary">Iniciar Sesión</a>
        </div>
    <?php endif; ?>
</main>

<script>
function habilitarBoton() {
    const input = document.getElementById('inputFoto');
    const boton = document.getElementById('botonCambiar');
    boton.disabled = !input.files.length;
}
</script>
</body>
</html>
