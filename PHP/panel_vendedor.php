<?php
session_start();

// Verifica si el usuario está autenticado y tiene el rol de 'vendedor'
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'vendedor') {
    header("Location: login.php");
    exit();
}

// Variables de sesión
$id_vendedor = $_SESSION['id_usuario'];
$rol_usuario = $_SESSION['rol'];
$foto_perfil = $_SESSION['foto_perfil'] ?? 'imagen_default.png'; // Si no hay foto, usa la default

// Conexión a la base de datos
require_once 'base_de_datos.php';

// Obtener propiedades del vendedor
$stmt = $pdo->prepare("SELECT * FROM propiedades WHERE id_vendedor = ?");
$stmt->execute([$id_vendedor]);
$propiedades = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Panel del Vendedor</title>
    <link rel="stylesheet" href="../CSS/estilos.css">
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
        <h2>Panel del Vendedor</h2>
        <div>
            <img src="fotos_perfil/<?php echo htmlspecialchars($foto_perfil); ?>" alt="Perfil" style="width: 60px; height: 60px; border-radius: 50%;">
        </div>
    </header>

    <div class="alerta-sesion">
        <strong>Sesión activa:</strong> ID Usuario = <code><?php echo htmlspecialchars($id_vendedor); ?></code>,
        Rol = <code><?php echo htmlspecialchars($rol_usuario); ?></code>
    </div>

    <div class="menu-container">
        <div id="menu-navegacion">
            <nav class="nav-separado">
                <a href="index.php">Inicio</a>
                <a href="vender.php">Nueva Propiedad</a>
                <a href="panel_vendedor.php">Propiedades</a>
                <a href="detallesCuenta.php">Cuenta</a>
                <a href="logout.php">Cerrar sesión</a>
            </nav>
        </div>
    </div>

    <main>
        <h1>Mis propiedades publicadas</h1>

        <?php if (count($propiedades) > 0): ?>
            <?php foreach ($propiedades as $propiedad): ?>
                <?php
                $stmtImg = $pdo->prepare("SELECT ruta_imagen FROM imagenes_propiedad WHERE id_propiedad = ? LIMIT 1");
                $stmtImg->execute([$propiedad['id_propiedad']]);
                $imagen = $stmtImg->fetchColumn();
                $ruta_imagen = $imagen ? $imagen : "imagenes/default.jpg";
                ?>
                <div class="propiedad-card d-flex mb-4" style="border: 1px solid #ccc; padding: 15px; border-radius: 10px;">
                    <div class="propiedad-imagen" style="width: 40%;">
                        <img src="<?= htmlspecialchars($ruta_imagen) ?>" alt="Imagen Propiedad" style="width: 100%; height: auto; border-radius: 8px;">
                    </div>
                    <div class="propiedad-detalles" style="width: 60%; padding-left: 20px;">
                        <h3><?= htmlspecialchars($propiedad['titulo']) ?></h3>
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
