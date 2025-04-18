<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}

include_once "base_de_datos.php";

$usuario = $_SESSION["usuario"];
$tipo = $_SESSION["tipo"];

// Asegurarse de que la columna `admin` esté definida
$isAdmin = isset($usuario["admin"]) && ($usuario["admin"] === true || $usuario["admin"] === "t" || $usuario["admin"] === "1");

// Obtener los valores únicos para los filtros
$ciudades = $pdo->query("SELECT DISTINCT municipio FROM propiedades ORDER BY municipio")->fetchAll(PDO::FETCH_COLUMN);
$estados = $pdo->query("SELECT DISTINCT estado FROM propiedades ORDER BY estado")->fetchAll(PDO::FETCH_COLUMN);
$empresas = $pdo->query("SELECT DISTINCT nombre FROM empresas ORDER BY nombre")->fetchAll(PDO::FETCH_COLUMN);
$tiposPropiedad = ["casa", "lote"]; // Valores fijos para el filtro

// Verificar si hay registros en las tablas
if (empty($ciudades)) $ciudades = ["No hay registros"];
if (empty($estados)) $estados = ["No hay registros"];
if (empty($empresas)) $empresas = ["No hay registros"];

// Filtros seleccionados
$ciudadSeleccionada = $_GET['ciudad'] ?? '';
$estadoSeleccionado = $_GET['estado'] ?? '';
$empresaSeleccionada = $_GET['empresa'] ?? '';
$tipoPropiedad = $_GET['tipo_propiedad'] ?? ''; // venta o renta
$tipoSeleccionado = $_GET['tipo'] ?? ''; // casa o lote

// Construir consulta dinámica para el filtro
$sql = "SELECT p.*, e.nombre AS empresa_nombre
        FROM propiedades p
        LEFT JOIN empresas e ON p.id_empresa = e.rfc
        WHERE 1=1";

$params = [];

if ($ciudadSeleccionada && $ciudadSeleccionada !== "No hay registros") {
    $sql .= " AND p.municipio = :ciudad";
    $params[':ciudad'] = $ciudadSeleccionada;
}

if ($estadoSeleccionado && $estadoSeleccionado !== "No hay registros") {
    $sql .= " AND p.estado = :estado";
    $params[':estado'] = $estadoSeleccionado;
}

if ($empresaSeleccionada && $empresaSeleccionada !== "No hay registros") {
    $sql .= " AND e.nombre = :empresa";
    $params[':empresa'] = $empresaSeleccionada;
}

if ($tipoPropiedad) {
    $sql .= " AND p.tipo_transaccion = :tipo";
    $params[':tipo'] = $tipoPropiedad;
}

if ($tipoSeleccionado) {
    $sql .= " AND p.tipo_propiedad = :tipo_propiedad";
    $params[':tipo_propiedad'] = $tipoSeleccionado;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$propiedades = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Cerrar sesión si se hace clic en el botón
if (isset($_POST['cerrar_sesion'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background: linear-gradient(to right, #A6A6A6, #4A90E2);
        /* Fondo difuminado */
        color: #333;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        height: 100vh;
    }

    header {
        background-color: #ffffff;
        /* Fondo blanco para la cabecera */
        color: #333;
        padding: 20px;
        text-align: center;
        width: 100%;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        position: relative;
    }

    header h1 {
        font-size: 2rem;
        color: #007bff;
    }

    .logout-btn {
        position: absolute;
        top: 10px;
        left: 10px;
        padding: 5px 10px;
        border: none;
        border-radius: 5px;
        background-color: #dc3545;
        color: white;
        cursor: pointer;
    }

    .logout-btn:hover {
        background-color: #c82333;
    }

    .credit-btn {
        padding: 10px 20px;
        background-color: #F28D52;
        /* Naranja */
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
    }

    .credit-btn:hover {
        background-color: #F27B50;
    }

    main {
        padding: 20px;
        max-width: 1200px;
        margin: auto;
        background-color: white;
        border-radius: 10px;
        box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.1);
        margin-top: 20px;
    }

    h2 {
        font-size: 1.5rem;
        color: #007bff;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    table thead {
        background-color: #F28D52;
        /* Naranja */
        color: white;
    }

    table th,
    table td {
        padding: 10px;
        border: 1px solid #ddd;
        text-align: center;
    }

    table tr:nth-child(even) {
        background-color: #f9f9f9;
        /* Gris muy claro */
    }

    table tr:hover {
        background-color: #e1e1e1;
    }

    table td {
        background-color: #F2F2F2;
        /* Gris claro */
    }

    .actions button {
        padding: 10px 15px;
        border: none;
        border-radius: 5px;
        background-color: #007bff;
        color: white;
        cursor: pointer;
    }

    .actions button:hover {
        background-color: #0056b3;
    }
    </style>
</head>

<body>
    <header>
        <h1>Bienvenido, <?php echo htmlspecialchars($usuario["nombre"]); ?></h1>
        <form method="POST">
            <button type="submit" name="cerrar_sesion" class="logout-btn">Cerrar sesión</button>
        </form>

        <a href="ingresarCredito.php"><button>Ingresar credito</button></a>
    </header>

    <main>
        <p><strong>Correo:</strong> <?php echo htmlspecialchars($usuario["correo"]); ?></p>
        <p><strong>Dirección:</strong>
            <?php echo !empty($usuario["direccion"]) ? htmlspecialchars($usuario["direccion"]) : "No disponible"; ?>
        </p>

        <div class="actions">
            <?php if ($isAdmin): ?>
            <a href="menuAdmin.php"><button>Acceder al menú de administración</button></a>
            <?php endif; ?>

            <?php if ($tipo === "empresa"): ?>
            <a href="registrarPropiedad.php"><button>Registrar propiedad nueva</button></a>
            <?php endif; ?>
        </div>

        <h2>Propiedades</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tipo</th>
                    <th>Municipio</th>
                    <th>Estado</th>
                    <th>Tamaño (m²)</th>
                    <th>Precio</th>
                    <th>Estado</th>
                    <th>Empresa</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($propiedades) > 0): ?>
                <?php foreach ($propiedades as $propiedad): ?>
                <tr>
                    <td><?php echo htmlspecialchars($propiedad['id_propiedad']); ?></td>
                    <td><?php echo htmlspecialchars($propiedad['tipo']); ?></td>
                    <td><?php echo htmlspecialchars($propiedad['municipio']); ?></td>
                    <td><?php echo htmlspecialchars($propiedad['estado']); ?></td>
                    <td><?php echo htmlspecialchars($propiedad['tamaño']); ?></td>
                    <td><?php echo htmlspecialchars($propiedad['precio']); ?></td>
                    <td><?php echo htmlspecialchars($propiedad['estado_propiedad']); ?></td>
                    <td><?php echo htmlspecialchars($propiedad['empresa_nombre']); ?></td>
                    <td>
                        <?php if ($propiedad['estado_propiedad'] === 'disponible'): ?>
                        <a href="detallePropiedad.php"><button>comprar</button></a>
                        <?php else: ?>
                        <span>No disponible</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php else: ?>
                <tr>
                    <td colspan="9">No se encontraron propiedades</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>
</body>

</html>