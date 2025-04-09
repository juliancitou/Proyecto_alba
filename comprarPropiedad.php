<?php
session_start();
include_once "base_de_datos.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['usuario'])) {
    header("Location: bienvenida.php");
    exit();
}

$usuario = $_SESSION['usuario'];
$idPropiedad = $_POST['id_propiedad'];
$precio = $_POST['precio'];
$empresaRFC = $_POST['empresa_rfc'];

// Verificar crédito del usuario
if ($usuario['credito'] < $precio) {
    echo "<script>
        if (confirm('Saldo insuficiente. ¿Deseas ingresar más crédito?')) {
            window.location.href = 'ingresarCredito.php';
        } else {
            window.location.href = 'bienvenida.php';
        }
    </script>";
    exit();
}

// Procesar compra
try {
    $pdo->beginTransaction();

    // Restar el crédito del usuario
    $sqlUsuario = "UPDATE usuarios SET credito = credito - :precio WHERE id_usuario = :id_usuario";
    $stmtUsuario = $pdo->prepare($sqlUsuario);
    $stmtUsuario->execute([':precio' => $precio, ':id_usuario' => $usuario['id_usuario']]);

    // Sumar el crédito a la empresa
    $sqlEmpresa = "UPDATE empresas SET credito = credito + :precio WHERE rfc = :empresa_rfc";
    $stmtEmpresa = $pdo->prepare($sqlEmpresa);
    $stmtEmpresa->execute([':precio' => $precio, ':empresa_rfc' => $empresaRFC]);

    // Marcar propiedad como vendida
    $sqlPropiedad = "UPDATE propiedades SET estado_propiedad = 'vendida' WHERE id_propiedad = :id";
    $stmtPropiedad = $pdo->prepare($sqlPropiedad);
    $stmtPropiedad->execute([':id' => $idPropiedad]);

    $pdo->commit();

    echo "<script>
        alert('Compra realizada con éxito.');
        window.location.href = 'bienvenida.php';
    </script>";
} catch (Exception $e) {
    $pdo->rollBack();
    echo "<script>
        alert('Error al procesar la compra: " . $e->getMessage() . "');
        window.location.href = 'bienvenida.php';
    </script>";
}
?>
