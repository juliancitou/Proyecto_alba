<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Usuario</title>
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

        /* Caja del formulario */
        .form-box {
            background-color: #ffffff;
            padding: 40px 50px;
            border-radius: 15px;
            box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
        }

        h1 {
            margin-bottom: 30px;
            font-size: 28px;
            color: #222626;
            text-align: center;
        }

        /* Inputs de formulario */
        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-size: 16px;
            margin-bottom: 8px;
            color: #222626;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: 1px solid #CCCCCC;
            border-radius: 8px;
            background-color: #ffffff;
            color: #222626;
            outline: none;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #52D5F2;
            box-shadow: 0px 0px 5px #52D5F2;
        }

        /* Botón de enviar */
        input[type="submit"] {
            width: 100%;
            padding: 14px;
            font-size: 18px;
            font-weight: bold;
            background-color: #F28D52;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 30px; /* Espaciado extra */
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        input[type="submit"]:hover {
            background-color: #52D5F2;
            transform: scale(1.03);
        }

        /* Ajuste del formulario */
        .form-box .form-group:last-of-type {
            margin-bottom: 0;
        }
    </style>
</head>
<body>
    <div class="form-box">
        <h1>Registrar Usuario</h1>
        <form method="POST" action="registrarUsuario.php">
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" placeholder="Ingresa tu nombre" required>
            </div>
            <div class="form-group">
                <label for="apellido">Apellido</label>
                <input type="text" id="apellido" name="apellido" placeholder="Ingresa tu apellido" required>
            </div>
            <div class="form-group">
                <label for="telefono">Teléfono</label>
                <input type="text" id="telefono" name="telefono" placeholder="Ingresa tu teléfono" required>
            </div>
            <div class="form-group">
                <label for="direccion">Dirección</label>
                <input type="text" id="direccion" name="direccion" placeholder="Ingresa tu dirección" required>
            </div>
            <div class="form-group">
                <label for="correo">Correo</label>
                <input type="email" id="correo" name="correo" placeholder="Ingresa tu correo" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" placeholder="Crea tu contraseña" required>
            </div>
            <input type="submit" value="Registrar">
        </form>
    </div>
</body>
</html>
