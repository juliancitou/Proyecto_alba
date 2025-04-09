<?php
include_once "base_de_datos.php";

// Verificar si se recibió el ID
if (!isset($_GET['id'])) {
    header("Location: menuAdmin.php");
    exit();
}

$id = $_GET['id'];
$sql = "SELECT * FROM usuarios WHERE id_usuario = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([":id" => $id]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    echo "Usuario no encontrado.";
    exit();
}

// Actualizar datos si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $telefono = $_POST["telefono"];
    $correo = $_POST["correo"];
    $admin = isset($_POST["admin"]) ? 1 : 0;

    $sql = "UPDATE usuarios SET nombre = :nombre, apellido = :apellido, telefono = :telefono, correo = :correo, admin = :admin WHERE id_usuario = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ":nombre" => $nombre,
        ":apellido" => $apellido,
        ":telefono" => $telefono,
        ":correo" => $correo,
        ":admin" => $admin,
        ":id" => $id
    ]);

    header("Location: menuAdmin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
</head>
<body>
    <h1>Editar Usuario</h1>
    <form method="POST">
        <input type="text" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>
        <input type="text" name="apellido" value="<?= htmlspecialchars($usuario['apellido']) ?>" required>
        <input type="text" name="telefono" value="<?= htmlspecialchars($usuario['telefono']) ?>">
        <input type="email" name="correo" value="<?= htmlspecialchars($usuario['correo']) ?>" required>
        <label>
            <input type="checkbox" name="admin" <?= $usuario['admin'] ? 'checked' : '' ?>> Administrador
        </label>
        <input type="submit" value="Actualizar">
    </form>
</body>
</html>
