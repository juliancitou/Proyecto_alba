<?php
include 'base_de_datos.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errorCorreo = '';
    $nombre = trim($_POST['nombre']);
    $apellidos = trim($_POST['apellidos']);
    $telefono = trim($_POST['telefono']);
    $edad = intval($_POST['edad']);
    $correo = trim($_POST['correo']);
    $direccion = trim($_POST['direccion']);
    $curp = strtoupper(trim($_POST['curp']));
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
    $rol = 'comprador';
    $foto_default = 'imagen_default.png';

    if (strlen($curp) !== 18) {
        echo "<script>alert('La CURP debe tener 18 caracteres');</script>";
        exit;
    }

    if ($edad < 0 || $edad > 120) {
        echo "<script>alert('Edad no válida');</script>";
        exit;
    }

    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Correo inválido');</script>";
        exit;
    }

    if (!preg_match('/^[0-9]{10}$/', $telefono)) {
        echo "<script>alert('El teléfono debe tener 10 dígitos');</script>";
        exit;
    }

    try {
        // Verificar si el correo ya existe
        $verificarCorreo = $pdo->prepare("SELECT * FROM usuarios WHERE correo = :correo");
        $verificarCorreo->execute(['correo' => $correo]);

        // Verificar si la CURP ya existe
        $verificarCurp = $pdo->prepare("SELECT * FROM usuarios WHERE curp = :curp");
        $verificarCurp->execute(['curp' => $curp]);

        if ($verificarCorreo->rowCount() > 0 || $verificarCurp->rowCount() > 0) {
            $errorCorreo = 'El correo o CURP ya están registrados.';
            $correo = ''; // Vacía el campo solo si hay error
        } else {
            // Insertar nuevo usuario
            $insertar = $pdo->prepare("INSERT INTO usuarios (nombre, apellidos, telefono, edad, correo, direccion, curp, contrasena, rol, foto_perfil)
            VALUES (:nombre, :apellidos, :telefono, :edad, :correo, :direccion, :curp, :password, :rol, :foto)");

            $insertar->execute([
                'nombre' => $nombre,
                'apellidos' => $apellidos,
                'telefono' => $telefono,
                'edad' => $edad,
                'correo' => $correo,
                'direccion' => $direccion,
                'curp' => $curp,
                'password' => $password,
                'rol' => $rol,
                'foto' => $foto_default
            ]);

            echo "<script>alert('Usuario registrado exitosamente'); window.location.href='login.php';</script>";
            exit;
        }
    } catch (PDOException $e) {
        echo "<script>alert('Error al registrar usuario: " . $e->getMessage() . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Registrar Usuario</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/registarUsuario.css">
    <link rel="stylesheet" href="../CSS/registrarUsuario_2.css">
</head>

<body>
    <div class="top-bar">
        <a href="login.php" class="btn-volver">Atrás</a>
        <div class="logo-section">
            <img src="../imagenes/logo_empresa.jpg" alt="Logo">
            <h2>MiEmpresa</h2>
        </div>
    </div>

    <div class="container">
        <h1>Registrar Usuario</h1>
        <form method="POST" action="registrarUsuario.php" id="formulario">
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" required>

            <label for="apellidos">Apellidos</label>
            <input type="text" name="apellidos" required>

            <label for="telefono" id="labelTelefono">Teléfono</label>
            <input type="text" name="telefono" id="telefono" maxlength="10" required>

            <label for="edad" id="labelEdad">Edad</label>
            <input type="text" name="edad" id="edad" maxlength="3" required>

            <?php if (!empty($errorCorreo)): ?>
                <p style="color:red; font-weight:bold; margin-bottom:5px;"><?php echo $errorCorreo; ?></p>
            <?php endif; ?>

            <label for="correo">Correo</label>
            <input type="email" name="correo" required
                value="<?php echo htmlspecialchars($correo ?? ''); ?>"
                style="<?php echo !empty($errorCorreo) ? 'border: 2px solid red;' : ''; ?>">

            <label for="direccion">Dirección</label>
            <input type="text" name="direccion" required>

            <label for="curp" id="labelCurp">CURP</label>
            <input type="text" name="curp" id="curp" maxlength="18" required>

            <label for="password" id="labelPass" class="password-toggle">
                Contraseña
                <input type="checkbox" onclick="togglePassword()"> Mostrar
            </label>
            <input type="password" name="password" id="password" placeholder="Contraseña" required autocomplete="new-password">

            <input type="submit" value="Registrar">
        </form>

        <hr>
        <h2>¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a></h2>
    </div>

    <script>
        function togglePassword() {
            const pwd = document.getElementById("password");
            pwd.type = pwd.type === "password" ? "text" : "password";
        }

        function validarCampo(inputId, labelId, minLength) {
            const input = document.getElementById(inputId);
            const label = document.getElementById(labelId);
            input.addEventListener("blur", () => {
                if (input.value.length < minLength) {
                    label.classList.remove("valid");
                    label.classList.add("invalid");
                } else {
                    label.classList.remove("invalid");
                    label.classList.add("valid");
                }
            });
        }

        document.getElementById('telefono').addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '');
        });

        document.getElementById('edad').addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '');
        });

        validarCampo("curp", "labelCurp", 18);
        validarCampo("telefono", "labelTelefono", 10);
        validarCampo("password", "labelPass", 4);
    </script>
</body>

</html>