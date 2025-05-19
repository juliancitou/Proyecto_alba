<?php
session_start();
include_once "base_de_datos.php";

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'vendedor') {
    header("Location: login.php");
    exit();
}

$id_vendedor = $_SESSION['usuario']['id_usuario'];
$id_propiedad = $_GET['id'] ?? null;

if (!$id_propiedad) {
    die("ID no válido");
}

// Cargar propiedad
$stmt = $pdo->prepare("SELECT * FROM propiedades WHERE id_propiedad = :id AND id_vendedor = :vendedor");
$stmt->execute([':id' => $id_propiedad, ':vendedor' => $id_vendedor]);
$propiedad = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$propiedad) {
    die("Propiedad no encontrada");
}

// Procesar edición
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $titulo = $_POST["titulo"];
    $descripcion = $_POST["descripcion"];
    $precio = $_POST["precio"];
    $estado_propiedad = $_POST["estado"];

    // Actualizar propiedad
    $stmt = $pdo->prepare("UPDATE propiedades SET titulo = :titulo, descripcion = :descripcion, precio = :precio, estado_propiedad = :estado WHERE id_propiedad = :id");
    $stmt->execute([
        ":titulo" => $titulo,
        ":descripcion" => $descripcion,
        ":precio" => $precio,
        ":estado" => $estado_propiedad,
        ":id" => $id_propiedad
    ]);

    // Manejo de nuevas imágenes
    if (!empty($_FILES["imagenes"]["name"][0])) {
        $ruta = "imagenes_propiedades/$id_propiedad";
        if (!is_dir($ruta)) {
            mkdir($ruta, 0777, true);
        }

        // Eliminar imágenes anteriores
        $archivos = array_diff(scandir($ruta), ['.', '..']);
        foreach ($archivos as $archivo) {
            unlink("$ruta/$archivo");
        }

        foreach ($_FILES["imagenes"]["tmp_name"] as $key => $tmp_name) {
            $nombre = basename($_FILES["imagenes"]["name"][$key]);
            $destino = "$ruta/" . uniqid() . "_" . $nombre;
            move_uploaded_file($tmp_name, $destino);
        }
    }

    header("Location: panel_vendedor.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Propiedad</title>
</head>
<body>
    <h1>Editar Propiedad</h1>
    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="titulo" value="<?= htmlspecialchars($propiedad['titulo']) ?>" required>
        <textarea name="descripcion" required><?= htmlspecialchars($propiedad['descripcion']) ?></textarea>
        <input type="number" name="precio" value="<?= $propiedad['precio'] ?>" required>
        <select name="estado">
            <option value="disponible" <?= $propiedad['estado_propiedad'] === 'disponible' ? 'selected' : '' ?>>Disponible</option>
            <option value="vendida" <?= $propiedad['estado_propiedad'] === 'vendida' ? 'selected' : '' ?>>Vendida</option>
        </select>
        <label>Nuevas imágenes (mínimo 5):</label>
        <input type="file" name="imagenes[]" multiple accept="image/*" required>
        <button type="submit">Guardar cambios</button>
    </form>
</body>
</html>
