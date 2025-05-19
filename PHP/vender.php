<?php
session_start();

$errores = [];
$datos = [
    'tipo_propiedad' => '',
    'direccion' => '',
    'pais' => '',
    'estado' => '',
    'municipio' => '',
    'metros_cuadrados' => '',
    'descripcion' => '',
    'precio' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($datos as $campo => &$valor) {
        if (isset($_POST[$campo]) && trim($_POST[$campo]) !== '') {
            $valor = htmlspecialchars(trim($_POST[$campo]));
        } else {
            $errores[$campo] = 'Este campo es obligatorio';
        }
    }

    if (!isset($_SESSION['usuario'])) {
        $errores['sesion'] = 'Debes iniciar sesión para publicar una propiedad.';
    }

    if (!isset($_FILES['imagenes']) || count(array_filter($_FILES['imagenes']['name'])) < 5) {
        $errores['imagenes'] = 'Debes subir al menos 5 imágenes.';
    }

    if (empty($errores)) {
        require_once 'base_de_datos.php';

        // Insertar propiedad
        $stmt = $pdo->prepare("INSERT INTO propiedades (tipo_propiedad, id_vendedor, direccion, pais, estado, municipio, metros_cuadrados, descripcion, precio) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $datos['tipo_propiedad'],
            $_SESSION['usuario']['id_usuario'],
            $datos['direccion'],
            $datos['pais'],
            $datos['estado'],
            $datos['municipio'],
            $datos['metros_cuadrados'],
            $datos['descripcion'],
            $datos['precio']
        ]);

        $id_propiedad = $pdo->lastInsertId();

        // Verificar si el usuario es "comprador" y actualizar a "vendedor"
        if ($_SESSION['usuario']['rol'] === 'comprador') {
            $stmtRol = $pdo->prepare("UPDATE usuarios SET rol = 'vendedor' WHERE id_usuario = ?");
            $stmtRol->execute([$_SESSION['usuario']['id_usuario']]);

            // Actualizar el rol en la sesión
            $_SESSION['usuario']['rol'] = 'vendedor';
        }


        $directorio = "../imagenes_propiedades/$id_propiedad";
        if (!is_dir($directorio)) {
            mkdir($directorio, 0777, true);
        }

        // Guardar imágenes y registrar en la tabla imagenes_propiedad
        $stmtImagen = $pdo->prepare("INSERT INTO imagenes_propiedad (id_propiedad, ruta_imagen) VALUES (?, ?)");

        foreach ($_FILES['imagenes']['tmp_name'] as $index => $tmpName) {
            if ($_FILES['imagenes']['error'][$index] === UPLOAD_ERR_OK) {
                $nombreArchivo = basename($_FILES['imagenes']['name'][$index]);
                $rutaRelativa = "imagenes_propiedades/$id_propiedad/$nombreArchivo";
                $rutaCompleta = "../$rutaRelativa";

                if (move_uploaded_file($tmpName, $rutaCompleta)) {
                    $stmtImagen->execute([$id_propiedad, $rutaRelativa]);
                }
            }
        }

        header("Location: index.php");
        exit();
    }
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
    <link rel="stylesheet" href="../CSS/formulario.css">
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
                <a href="ultima_visita.php">Seguir Comprando</a>
                <a href="detallesCuenta.php">Cuenta</a>
                <a href="menu.php">Menú</a>
            </nav>
        </div>
    </div>
    <?php include 'menu.php'; ?>
    <main class="form-container">
        <h2>Publicar una Propiedad</h2>
        <?php if (isset($errores['sesion'])): ?><p class="error"><?= $errores['sesion'] ?></p><?php endif; ?>
        <form action="vender.php" method="POST" enctype="multipart/form-data">
            <label>Tipo de Propiedad:</label>
            <select name="tipo_propiedad" class="<?= isset($errores['tipo_propiedad']) ? 'input-error' : '' ?>">
                <option value="">Seleccionar</option>
                <option value="casa" <?= $datos['tipo_propiedad'] === 'casa' ? 'selected' : '' ?>>Vivienda</option>
                <option value="terreno" <?= $datos['tipo_propiedad'] === 'terreno' ? 'selected' : '' ?>>Terreno</option>
            </select>
            <?php if (isset($errores['tipo_propiedad'])): ?><span class="error"><?= $errores['tipo_propiedad'] ?></span><?php endif; ?>

            <label>Dirección:</label>
            <input type="text" name="direccion" value="<?= $datos['direccion'] ?>" class="<?= isset($errores['direccion']) ? 'input-error' : '' ?>">
            <?php if (isset($errores['direccion'])): ?><span class="error"><?= $errores['direccion'] ?></span><?php endif; ?>

            <label>País:</label>
            <input type="text" name="pais" value="<?= $datos['pais'] ?>" class="<?= isset($errores['pais']) ? 'input-error' : '' ?>">
            <?php if (isset($errores['pais'])): ?><span class="error"><?= $errores['pais'] ?></span><?php endif; ?>

            <label>Estado:</label>
            <input type="text" name="estado" value="<?= $datos['estado'] ?>" class="<?= isset($errores['estado']) ? 'input-error' : '' ?>">
            <?php if (isset($errores['estado'])): ?><span class="error"><?= $errores['estado'] ?></span><?php endif; ?>

            <label>Municipio:</label>
            <input type="text" name="municipio" value="<?= $datos['municipio'] ?>" class="<?= isset($errores['municipio']) ? 'input-error' : '' ?>">
            <?php if (isset($errores['municipio'])): ?><span class="error"><?= $errores['municipio'] ?></span><?php endif; ?>

            <label>Metros cuadrados:</label>
            <input type="number" step="0.01" name="metros_cuadrados" value="<?= $datos['metros_cuadrados'] ?>" class="<?= isset($errores['metros_cuadrados']) ? 'input-error' : '' ?>">
            <?php if (isset($errores['metros_cuadrados'])): ?><span class="error"><?= $errores['metros_cuadrados'] ?></span><?php endif; ?>

            <label>Descripción:</label>
            <textarea name="descripcion" class="<?= isset($errores['descripcion']) ? 'input-error' : '' ?>"><?= $datos['descripcion'] ?></textarea>
            <?php if (isset($errores['descripcion'])): ?><span class="error"><?= $errores['descripcion'] ?></span><?php endif; ?>

            <label>Precio:</label>
            <input type="number" step="0.01" name="precio" value="<?= $datos['precio'] ?>" class="<?= isset($errores['precio']) ? 'input-error' : '' ?>">
            <?php if (isset($errores['precio'])): ?><span class="error"><?= $errores['precio'] ?></span><?php endif; ?>

            <label>Imágenes (al menos 5):</label>
            <input type="file" name="imagenes[]" multiple accept="image/*" class="<?= isset($errores['imagenes']) ? 'input-error' : '' ?>">
            <?php if (isset($errores['imagenes'])): ?><span class="error"><?= $errores['imagenes'] ?></span><?php endif; ?>

            <button type="submit">Publicar Propiedad</button>
        </form>
    </main>
</body>

</html>