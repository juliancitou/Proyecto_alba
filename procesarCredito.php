<?php
session_start();
include_once "base_de_datos.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $numeroTarjeta = str_replace(" ", "", $_POST["numeroTarjeta"]); // Elimina espacios
    $cantidad = (int) $_POST["cantidad"];
    $usuario = $_SESSION["usuario"];
    $tipo = $_SESSION["tipo"];

    // Validar el número de tarjeta
    if (strlen($numeroTarjeta) !== 16 || !is_numeric($numeroTarjeta)) {
        echo "Error: Número de tarjeta inválido.";
        exit();
    }

    // Verificar la clave primaria según el tipo de usuario
    $clavePrimaria = $tipo === "empresa" ? "rfc" : "id_usuario";

    // Verificar que la clave primaria esté en la sesión
    if (!isset($usuario[$clavePrimaria])) {
        echo "Error: No se pudo determinar el identificador del usuario.";
        exit();
    }

    // Seleccionar la tabla correspondiente
    $tabla = $tipo === "empresa" ? "empresas" : "usuarios";

    // Actualizar el saldo en la base de datos
    $sql = "UPDATE $tabla SET credito = credito + :cantidad WHERE $clavePrimaria = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ":cantidad" => $cantidad,
        ":id" => $usuario[$clavePrimaria]
    ]);

    echo "Crédito actualizado exitosamente.";
    header("Location: bienvenida.php");
    exit();
}
?>
