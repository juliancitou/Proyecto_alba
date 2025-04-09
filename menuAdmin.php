<?php
include_once "base_de_datos.php";
session_start();

// Verificar que el usuario sea administrador
if (!isset($_SESSION['usuario']) || !$_SESSION['usuario']['admin']) {
    header("Location: login.php");
    exit();
}

// Consultar todos los usuarios
$sql = "SELECT * FROM usuarios";
$stmt = $pdo->query($sql);
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Menú de Administración</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>Menú de Administración</h1>
    <a href="logout.php">Cerrar Sesión</a>
    <h2>Usuarios Registrados</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Teléfono</th>
                <th>Correo</th>
                <th>Admin</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $usuario): ?>
            <tr>
                <td><?= htmlspecialchars($usuario['id_usuario']) ?></td>
                <td><?= htmlspecialchars($usuario['nombre']) ?></td>
                <td><?= htmlspecialchars($usuario['apellido']) ?></td>
                <td><?= htmlspecialchars($usuario['telefono']) ?></td>
                <td><?= htmlspecialchars($usuario['correo']) ?></td>
                <td><?= $usuario['admin'] ? 'Sí' : 'No' ?></td>
                <td>
                    <a href="editarUsuario.php?id=<?= $usuario['id_usuario'] ?>">Editar</a>
                    <a href="eliminarUsuario.php?id=<?= $usuario['id_usuario'] ?>" 
                       onclick="return confirm('¿Estás seguro de que deseas eliminar este usuario?');">Eliminar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
