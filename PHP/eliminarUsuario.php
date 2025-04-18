<?php
include_once "base_de_datos.php";

if (!isset($_GET['id'])) {
    header("Location: menuAdmin.php");
    exit();
}

$id = $_GET['id'];
$sql = "DELETE FROM usuarios WHERE id_usuario = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([":id" => $id]);

header("Location: menuAdmin.php");
exit();
?>
