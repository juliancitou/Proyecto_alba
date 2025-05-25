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
    $tipo_propiedad = $_POST["tipo_propiedad"];
    $descripcion = $_POST["descripcion"];
    $precio = $_POST["precio"];
    $estado_propiedad = $_POST["estado"];

    // Actualizar propiedad
    $stmt = $pdo->prepare("UPDATE propiedades SET tipo_propiedad = :tipo, descripcion = :descripcion, precio = :precio, estado_propiedad = :estado WHERE id_propiedad = :id");
    $stmt->execute([
        ":tipo" => $tipo_propiedad,
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../CSS/estilos.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        header {
            padding: 15px 30px;
            background-color: rgb(0, 0, 0);
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #ccc;
        }

        .logo-img {
            height: 60px;
            margin-right: 15px;
        }

        h1 {
            margin-top: 30px;
            text-align: center;
            font-weight: bold;
        }

        .card {
            margin: 40px auto;
            max-width: 700px;
            box-shadow: 0px 0px 12px rgba(0, 0, 0, 0.1);
        }

        .form-label {
            font-weight: 500;
        }

        .btn-primary {
            background-color: #ff9900;
            border-color: #ff9900;
        }

        .btn-primary:hover {
            background-color: #cc7a00;
            border-color: #cc7a00;
        }

        .btn-cancelar {
            background-color: #6c757d;
            border-color: #6c757d;
        }

        .btn-cancelar:hover {
            background-color: #5a6268;
            border-color: #545b62;
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
            <a href="login.php"><button class="btn btn-warning">Iniciar Sesión</button></a>
        <?php else: ?>
            <div class="d-flex align-items-center">
                <span class="me-3">Hola, <?= htmlspecialchars($_SESSION['usuario']['nombre'] . ' ' . $_SESSION['usuario']['apellidos']) ?></span>
                <a href="logout.php" class="btn btn-outline-secondary">Cerrar sesión</a>
            </div>
        <?php endif; ?>
    </header>

    <h1>Editar Propiedad</h1>

    <div class="card p-4">
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="tipo_propiedad" class="form-label">Tipo de propiedad:</label>
                <select class="form-select" name="tipo_propiedad" id="tipo_propiedad" required>
                    <option value="casa" <?= strtolower($propiedad['tipo_propiedad']) === 'casa' ? 'selected' : '' ?>>Casa</option>
                    <option value="terreno" <?= strtolower($propiedad['tipo_propiedad']) === 'terreno' ? 'selected' : '' ?>>Terreno</option>

                </select>
            </div>


            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción:</label>
                <textarea class="form-control" name="descripcion" id="descripcion" rows="4" required><?= htmlspecialchars($propiedad['descripcion']) ?></textarea>
            </div>

            <div class="mb-3">
                <label for="precio" class="form-label">Precio (MXN):</label>
                <input type="number" class="form-control" name="precio" id="precio" value="<?= $propiedad['precio'] ?>" required>
            </div>

            <div class="mb-3">
                <label for="estado" class="form-label">Estado de la propiedad:</label>
                <select class="form-select" name="estado" id="estado">
                    <option value="disponible" <?= $propiedad['estado_propiedad'] === 'disponible' ? 'selected' : '' ?>>Disponible</option>
                    <option value="vendida" <?= $propiedad['estado_propiedad'] === 'vendida' ? 'selected' : '' ?>>Vendida</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Nuevas imágenes (mínimo 5):</label>
                <input type="file" class="form-control" name="imagenes[]" multiple accept="image/*" required>
            </div>

            <div class="d-flex justify-content-between">
                <a href="panel_vendedor.php" class="btn btn-cancelar">Cancelar</a>
                <button type="submit" class="btn btn-primary">Guardar cambios</button>
            </div>
        </form>
    </div>
</body>

</html>