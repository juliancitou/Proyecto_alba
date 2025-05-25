<?php
session_start();
require_once 'base_de_datos.php'; // Conexión usando PDO

// Verifica si hay sesión activa
if (!isset($_SESSION['usuario']) || !isset($_SESSION['tipo'])) {
    echo "<h3>No hay sesión activa. Inicia sesión primero.</h3>";
    echo '<a href="login.php">Ir a login</a>';
    exit();
}

$tipo = $_SESSION['tipo'];

if ($tipo === "usuario") {
    $id_usuario = $_SESSION['usuario']['id_usuario'];
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id_usuario = ?");
    $stmt->execute([$id_usuario]);
    $usuarioActualizado = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuarioActualizado) {
        $_SESSION['usuario'] = $usuarioActualizado;
        $_SESSION['rol'] = $usuarioActualizado['rol'];
    } else {
        echo "<h3>No se encontró al usuario en la base de datos.</h3>";
        exit();
    }

    $id_mostrar = $usuarioActualizado['id_usuario'];
    $rol = $usuarioActualizado['rol'];

} else if ($tipo === "empresa") {
    $rfc = $_SESSION['usuario']['rfc'];
    $stmt = $pdo->prepare("SELECT * FROM empresas WHERE rfc = ?");
    $stmt->execute([$rfc]);
    $empresaActualizada = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($empresaActualizada) {
        $_SESSION['usuario'] = $empresaActualizada;
        $_SESSION['rol'] = 'empresa';
    } else {
        echo "<h3>No se encontró la empresa en la base de datos.</h3>";
        exit();
    }

    $id_mostrar = $empresaActualizada['rfc'];
    $rol = "empresa";
} else {
    echo "<h3>Tipo de sesión no válido.</h3>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Verificar Rol del Usuario</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
            background-color: #f0f0f0;
        }
        .contenedor {
            background-color: white;
            padding: 25px;
            border-radius: 8px;
            width: 350px;
            margin: auto;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        .boton {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: orange;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
        }
        .boton:hover {
            background-color: darkorange;
        }
    </style>
</head>
<body>

    <div class="contenedor">
        <h2>Datos de sesión actual</h2>
        <p><strong>ID <?= ($tipo === "usuario") ? 'Usuario' : 'Empresa' ?>:</strong> <?= htmlspecialchars($id_mostrar) ?></p>
        <p><strong>Rol (actualizado desde BD):</strong> <?= htmlspecialchars($rol) ?></p>

        <form action="panel_vendedor.php" method="get">
            <button class="boton" type="submit">Continuar</button>
        </form>
    </div>

</body>
</html>
