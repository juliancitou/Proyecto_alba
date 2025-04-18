<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Propiedad</title>
    <style>
        /* Estilos globales */
        body {
            margin: 0;
            padding: 0;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: #F2F2F2;
            color: #222626;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Contenedor centrado */
        .container {
            background-color: #ffffff;
            padding: 20px 30px;
            border-radius: 15px;
            box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 800px;
        }

        h1 {
            margin-bottom: 20px;
            font-size: 24px;
            text-align: center;
            color: #222626;
        }

        fieldset {
            border: none;
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f7f7f7;
            border-radius: 10px;
        }

        legend {
            font-weight: bold;
            color: #222626;
        }

        /* Estilo para los campos de entrada */
        label {
            display: block;
            margin-bottom: 5px;
            font-size: 14px;
            color: #222626;
        }

        input[type="number"],
        input[type="text"],
        select {
            width: 90%;
            padding: 8px;
            margin: 5px 0 15px 0;
            font-size: 14px;
            border: 1px solid #cccccc;
            border-radius: 5px;
            background-color: #ffffff;
            color: #222626;
            outline: none;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        input[type="number"]:focus,
        input[type="text"]:focus,
        select:focus {
            border-color: #52D5F2;
            box-shadow: 0px 0px 5px #52D5F2;
        }

        /* Diseño en dos columnas para campos generales */
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        /* Botón de submit */
        input[type="submit"] {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            font-weight: bold;
            background-color: #F28D52;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        input[type="submit"]:hover {
            background-color: #52D5F2;
            transform: scale(1.03);
        }
    </style>
    <script>
        // Función para cambiar los campos según la selección del radio botón
        function cambiarFormulario() {
            const tipoPropiedad = document.querySelector('input[name="tipo"]:checked').value;
            document.getElementById("formCasa").style.display = tipoPropiedad === "casa" ? "block" : "none";
            document.getElementById("formLote").style.display = tipoPropiedad === "lote" ? "block" : "none";
        }
    </script>
</head>

<body>
    <div class="container">
        <h1>Registrar Propiedad</h1>
        <form method="POST" action="guardarPropiedad.php">
            <!-- Campos generales para propiedades -->
            <fieldset>
                <legend>Datos Generales de la Propiedad</legend>
                <div class="form-row">
                    <div>
                        <label for="tamano">Tamaño en m²:</label>
                        <input type="number" name="tamano" id="tamano" required><br>
                    </div>
                    <div>
                        <label for="direccion">Dirección:</label>
                        <input type="text" name="direccion" id="direccion" required><br>
                    </div>
                </div>
                <div class="form-row">
                    <div>
                        <label for="pais">País:</label>
                        <input type="text" name="pais" id="pais" required><br>
                    </div>
                    <div>
                        <label for="municipio">Municipio:</label>
                        <input type="text" name="municipio" id="municipio" required><br>
                    </div>
                </div>
                <div class="form-row">
                    <div>
                        <label for="estado">Estado:</label>
                        <input type="text" name="estado" id="estado" required><br>
                    </div>
                    <div>
                        <label for="precio">Precio:</label>
                        <input type="number" name="precio" id="precio" required><br>
                    </div>
                </div>
            </fieldset>

            <!-- Selección entre casa y lote -->
            <fieldset>
                <legend>Tipo de Propiedad</legend>
                <label>
                    <input type="radio" name="tipo" value="casa" checked onclick="cambiarFormulario()"> Casa
                </label>
                <label>
                    <input type="radio" name="tipo" value="lote" onclick="cambiarFormulario()"> Lote
                </label>
            </fieldset>

            <!-- Campos específicos para casa -->
            <fieldset id="formCasa" style="display: block;">
                <legend>Detalles de la Casa</legend>
                <div class="form-row">
                    <div>
                        <label for="pisos">Número de pisos:</label>
                        <input type="number" name="pisos" id="pisos" required><br>
                    </div>
                    <div>
                        <label for="banos">Número de baños:</label>
                        <input type="number" name="banos" id="banos" required><br>
                    </div>
                </div>
                <div class="form-row">
                    <div>
                    <label for="cuartos">Número de cuartos (habitaciones):</label>
                    <input type="number" name="cuartos" id="cuartos" required>  
                    </div>
                </div>
            </fieldset>

            <!-- Campos específicos para lote -->
            <fieldset id="formLote" style="display: none;">
                <legend>Detalles del Lote</legend>
                <label for="cimentacion">¿Tiene cimentación?</label>
                <select name="cimentacion" id="cimentacion" required>
                    <option value="si">Sí</option>
                    <option value="no">No</option>
                </select><br>
            </fieldset>

            <input type="submit" value="Registrar">
        </form>
    </div>
</body>

</html>
