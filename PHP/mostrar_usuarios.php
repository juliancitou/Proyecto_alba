<?php
// Incluir conexión a la base de datos
include_once "base_de_datos.php";

try {
    // Consulta para obtener datos de la tabla 'usuarios'
    $sql = "SELECT * FROM usuarios";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    // Mostrar resultados
    echo "<table border='1'>";
    echo "<tr>";
    for ($i = 0; $i < $stmt->columnCount(); $i++) {
        $colMeta = $stmt->getColumnMeta($i);
        echo "<th>" . htmlspecialchars($colMeta['name']) . "</th>";
    }
    echo "</tr>";

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        foreach ($row as $value) {
            echo "<td>" . htmlspecialchars($value) . "</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
} catch (PDOException $e) {
    echo "Error al consultar los datos: " . $e->getMessage();
}
?>
