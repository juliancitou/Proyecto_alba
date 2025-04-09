<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Seleccionar Registro</title>
    <style>
        body {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(to right, #A6A6A6, #4A90E2); /* Fondo difuminado */
        }

        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 100%;
            max-width: 400px;
        }

        h1 {
            font-size: 28px;
            color: #222626;
            margin-bottom: 20px;
        }

        .btn-regresar {
            position: absolute;
            top: 20px;
            left: 20px;
            padding: 10px 20px;
            font-size: 16px;
            background-color: #F28D52;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-regresar:hover {
            background-color: #52D5F2;
        }

        button {
            padding: 14px 28px;
            margin: 10px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            border: none;
            border-radius: 8px;
            background-color: #F28D52;
            color: white;
            transition: background-color 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1); /* Sombra suave */
        }

        button:hover {
            background-color: #52D5F2;
            transform: scale(1.05);
            box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.15); /* Sombra más intensa en hover */
        }

        button:disabled {
            background-color: #cccccc;
            cursor: not-allowed;
        }
    </style>
</head>
<body>

    <!-- Botón Regresar -->
    <a href="login.php" class="btn-regresar">Regresar</a>

    <div class="container">
        <h1>¿Cómo deseas registrarte?</h1>
        
        <form action="registrarUsuario.php" method="GET">
            <button type="submit">Registrarme como Usuario</button>
        </form>
        
        <form action="registrarEmpresa.php" method="GET">
            <button type="submit">Registrarme como Empresa</button>
        </form>
    </div>

</body>
</html>
