<?php
session_start();
include_once "base_de_datos.php";

// Solo para depuración, elimina esta línea en producción
var_dump($_POST); // Verifica si los datos se están enviando correctamente

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Recibir los datos del formulario
    $tamano = $_POST["tamano"];
    $direccion = $_POST["direccion"];
    $municipio = $_POST["municipio"];
    $estado = $_POST["estado"];
    $pais = $_POST["pais"];
    $precio = $_POST["precio"];
    $tipo = $_POST["tipo"];
    $rfc_empresa = $_SESSION["usuario"]["rfc"]; // Usamos el RFC de la empresa desde la sesión

    try {
        // Iniciar la transacción
        $pdo->beginTransaction();

        // Insertar en la tabla "propiedades"
        $sqlPropiedad = "INSERT INTO propiedades (tamaño, direccion, municipio, estado, pais, precio, estado_propiedad, id_empresa, tipo)
                         VALUES (:tamano, :direccion, :municipio, :estado, :pais, :precio, 'disponible', :id_empresa, :tipo)";
        $stmtPropiedad = $pdo->prepare($sqlPropiedad);
        $stmtPropiedad->execute([
            ":tamano" => $tamano,
            ":direccion" => $direccion,
            ":municipio" => $municipio,
            ":estado" => $estado,
            ":pais" => $pais,
            ":precio" => $precio,
            ":id_empresa" => $rfc_empresa,
            ":tipo" => $tipo
        ]);

        $propiedadId = $pdo->lastInsertId(); // Obtener el ID de la propiedad recién insertada

        // Insertar en la tabla específica según el tipo de propiedad
        if ($tipo === "casa") {
            if (isset($_POST["pisos"], $_POST["banos"], $_POST["cuartos"])) {
                $num_pisos = $_POST["pisos"];
                $num_baños = $_POST["banos"];
                $num_cuartos = $_POST["cuartos"]; // Número de cuartos

                // Insertar en la tabla "casas"
                $sqlCasa = "INSERT INTO casas (id_propiedad, num_pisos, num_banos, num_cuartos) 
                            VALUES (:id_propiedad, :num_pisos, :num_banos, :num_cuartos)";
                $stmtCasa = $pdo->prepare($sqlCasa);
                $stmtCasa->execute([
                    ":id_propiedad" => $propiedadId,
                    ":num_pisos" => $num_pisos,
                    ":num_banos" => $num_baños,
                    ":num_cuartos" => $num_cuartos
                ]);
            } else {
                echo "Faltan los datos de pisos, baños o cuartos.";
            }
        } else if ($tipo === "lote") {
            if (isset($_POST["cimentacion"])) {
                $tipo_cimentacion = $_POST["cimentacion"];

                // Insertar en la tabla "lotes"
                $sqlLote = "INSERT INTO lotes (id_propiedad, tipo_cimentacion) 
                            VALUES (:id_propiedad, :tipo_cimentacion)";
                $stmtLote = $pdo->prepare($sqlLote);
                $stmtLote->execute([
                    ":id_propiedad" => $propiedadId,
                    ":tipo_cimentacion" => $tipo_cimentacion
                ]);
            } else {
                echo "Faltan los datos de cimentación.";
            }
        }

        // Confirmar la transacción
        $pdo->commit();

        // Redirigir a la página de bienvenida
        header("Location: bienvenida.php");
        exit();

    } catch (Exception $e) {
        // Revertir en caso de error
        $pdo->rollBack();
        die("Error al registrar la propiedad: " . $e->getMessage());
    }
}
?>
