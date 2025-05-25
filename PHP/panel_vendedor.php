<?php
session_start();
require_once 'base_de_datos.php'; // Conexión PDO

// Verifica sesión activa
if (!isset($_SESSION['usuario']) || !isset($_SESSION['tipo'])) {
    echo "<h3>No hay sesión activa. Inicia sesión primero.</h3>";
    echo '<a href="login.php">Ir a login</a>';
    exit();
}

$tipo = $_SESSION['tipo'];
$usuario = $_SESSION['usuario'];
$foto_perfil = "imagen_default.png"; // Valor por defecto

// Cargar datos según tipo
if ($tipo === "usuario") {
    $id_usuario = $usuario['id_usuario'];
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id_usuario = ?");
    $stmt->execute([$id_usuario]);
    $usuarioActualizado = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuarioActualizado) {
        $_SESSION['usuario'] = $usuarioActualizado;
        $_SESSION['rol'] = $usuarioActualizado['rol'];
        $foto_perfil = $usuarioActualizado['foto_perfil'] ?? "imagen_default.png";
        $id_mostrar = $usuarioActualizado['id_usuario'];
        $rol_usuario = $usuarioActualizado['rol'];
    } else {
        echo "<h3>No se encontró al usuario.</h3>";
        exit();
    }
} elseif ($tipo === "empresa") {
    $rfc = $usuario['rfc'];
    $stmt = $pdo->prepare("SELECT * FROM empresas WHERE rfc = ?");
    $stmt->execute([$rfc]);
    $empresaActualizada = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($empresaActualizada) {
        $_SESSION['usuario'] = $empresaActualizada;
        $_SESSION['rol'] = 'empresa';
        $foto_perfil = $empresaActualizada['foto_perfil'] ?? "imagen_default.png";
        $id_mostrar = $empresaActualizada['rfc'];
        $rol_usuario = "empresa";
    } else {
        echo "<h3>No se encontró la empresa.</h3>";
        exit();
    }
} else {
    echo "<h3>Tipo de sesión no válido.</h3>";
    exit();
}

// Obtener propiedades del vendedor
$stmt = $pdo->prepare("SELECT * FROM propiedades WHERE id_vendedor = ?");
$stmt->execute([$id_mostrar]);
$propiedades = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../CSS/estilos.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .alerta-sesion {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
            padding: 10px 15px;
            margin: 10px auto;
            width: 95%;
            border-radius: 5px;
            text-align: center;
            font-size: 14px;
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
                <a href="verificar_rol.php">Propiedades</a>
                <a href="detallesCuenta.php">Cuenta</a>
                <a href="menu.php">Menú</a>
            </nav>
        </div>
    </div>

    <main>
        <h1>Mis propiedades publicadas</h1>

        <?php if (!empty($propiedades)): ?>
            <?php foreach ($propiedades as $propiedad): ?>
                <?php
                $stmtImg = $pdo->prepare("SELECT ruta_imagen FROM imagenes_propiedad WHERE id_propiedad = ? LIMIT 1");
                $stmtImg->execute([$propiedad['id_propiedad']]);
                $imagen = $stmtImg->fetchColumn();
                $ruta_imagen = $imagen ? "../$imagen" : "../imagenes/default.jpg";
                ?>
                <div class="propiedad-card d-flex mb-4" style="border: 1px solid #ccc; padding: 15px; border-radius: 10px;">
                    <div class="propiedad-imagen" style="width: 40%;">
                        <img src="<?= htmlspecialchars($ruta_imagen) ?>" alt="Imagen de la propiedad" class="img-fluid rounded shadow">
                    </div>
                    <div class="propiedad-detalles" style="width: 60%; padding-left: 20px;">
                        <h3><?= htmlspecialchars($propiedad['tipo_propiedad']) ?></h3>
                        <p><?= htmlspecialchars($propiedad['descripcion']) ?></p>
                        <p><strong>Precio:</strong> $<?= number_format($propiedad['precio'], 2) ?></p>
                        <p><strong>Ubicación:</strong> <?= htmlspecialchars("{$propiedad['direccion']}, {$propiedad['municipio']}, {$propiedad['estado']}, {$propiedad['pais']}") ?></p>
                        <a href="editar_propiedad.php?id=<?= $propiedad['id_propiedad'] ?>" class="btn-detalles">Modificar</a>
                        <a href="eliminar_propiedad.php?id=<?= $propiedad['id_propiedad'] ?>" class="btn-detalles" onclick="return confirm('¿Estás seguro de eliminar esta propiedad?');">Eliminar</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No tienes propiedades publicadas aún.</p>
        <?php endif; ?>
    </main>

</body>

</html>