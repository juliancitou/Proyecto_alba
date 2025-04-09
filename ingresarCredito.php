<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}

$usuario = $_SESSION["usuario"];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ingresar Crédito</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }

        header {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            position: relative;
            text-align: center;
        }

        header .regresar {
            position: absolute;
            left: 10px;
            top: 10px;
            background-color: white;
            color: #007bff;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.9rem;
        }

        header .regresar:hover {
            background-color: #e0e0e0;
        }

        main {
            padding: 20px;
            max-width: 600px;
            margin: auto;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        h1 {
            font-size: 2rem;
            color: #007bff;
            text-align: center;
        }

        label {
            font-weight: bold;
            display: block;
            margin-top: 15px;
        }

        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }

        input[type="text"].error,
        input[type="number"].error {
            border-color: red;
        }

        .error {
            color: red;
            font-size: 0.9rem;
        }

        button {
            display: block;
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
            font-size: 1rem;
            cursor: pointer;
            margin-top: 20px;
        }

        button:hover {
            background-color: #0056b3;
        }

        .form-container {
            margin-top: 20px;
        }
    </style>
    <script>
        function validarFormulario(event) {
            event.preventDefault();

            const tarjetaInput = document.getElementById("numeroTarjeta");
            const cantidadInput = document.getElementById("cantidad");
            const tarjetaError = document.getElementById("tarjetaError");
            const cantidadError = document.getElementById("cantidadError");

            let isValid = true;

            // Validación del número de tarjeta
            const tarjeta = tarjetaInput.value.replace(/\s+/g, ""); // Eliminar espacios
            if (tarjeta.length !== 16 || isNaN(tarjeta)) {
                tarjetaError.textContent = "Número de tarjeta incorrecto (16 dígitos requeridos)";
                tarjetaInput.classList.add("error");
                isValid = false;
            } else {
                tarjetaError.textContent = "";
                tarjetaInput.classList.remove("error");
            }

            // Validación de la cantidad
            const cantidad = parseFloat(cantidadInput.value);
            if (isNaN(cantidad) || cantidad <= 0) {
                cantidadError.textContent = "Ingrese una cantidad válida mayor a 0";
                cantidadInput.classList.add("error");
                isValid = false;
            } else {
                cantidadError.textContent = "";
                cantidadInput.classList.remove("error");
            }

            // Enviar el formulario si todo es correcto
            if (isValid) {
                event.target.submit();
            }
        }

        function formatearTarjeta(event) {
            const input = event.target;
            const valor = input.value.replace(/\D/g, ""); // Eliminar caracteres no numéricos
            const grupos = valor.match(/.{1,4}/g) || []; // Dividir en grupos de 4
            input.value = grupos.join(" "); // Unir con espacios
        }
    </script>
</head>
<body>
    <header>
        <a href="bienvenida.php" class="regresar">Regresar</a>
        <h1>Ingresar Crédito</h1>
    </header>
    <main>
        <div class="form-container">
            <form method="POST" action="procesarCredito.php" onsubmit="validarFormulario(event)">
                <label for="numeroTarjeta">Número de Tarjeta:</label>
                <input type="text" id="numeroTarjeta" name="numeroTarjeta" maxlength="19" oninput="formatearTarjeta(event)" required>
                <span id="tarjetaError" class="error"></span>

                <label for="cantidad">Cantidad a Ingresar:</label>
                <input type="number" id="cantidad" name="cantidad" min="1" required>
                <span id="cantidadError" class="error"></span>

                <button type="submit">Aceptar</button>
            </form>
        </div>
    </main>
</body>
</html>
