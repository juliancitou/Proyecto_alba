<?php
session_start();
if (!isset($_SESSION["usuario"]) || $_SESSION["usuario"]["admin"] !== "t") {
    header("Location: bienvenida.php");
    exit();
}

include_once "base_de_datos.php";

// Consulta para obtener todos los usuarios
$sql = "SELECT * FROM usuarios";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Administración</title>
</head>

<body>
    <h1>Usuarios Registrados</h1>
    <table border="1">
        <tr>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Correo</th>
            <th>Teléfono</th>
            <th>Dirección</th>
        </tr>
        <?php foreach ($usuarios as $usuario): ?>
        <tr>
            <td><?php echo htmlspecialchars($usuario["nombre"]); ?></td>
            <td><?php echo htmlspecialchars($usuario["apellido"]); ?></td>
            <td><?php echo htmlspecialchars($usuario["correo"]); ?></td>
            <td><?php echo htmlspecialchars($usuario["telefono"]); ?></td>
            <td><?php echo htmlspecialchars($usuario["direccion"]); ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>

</html>