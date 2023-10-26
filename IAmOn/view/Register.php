<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/register.css">
    <title>Registro - IAmOn</title>
</head>
<body>
    <div class="container">
        <header>
            <h1>Registro en IAmOn</h1>
        </header>
        <main>
            <form method="post" action="RegistrarUsuario.php">
                <label for="username">Usuario:</label>
                <input type="text" id="username" name="username" required>

                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>

                <label for="email">Correo (si deseas recibir notificaciones):</label>
                <input type="email" id="email" name="email">

                <button type="submit">Registrarse</button>
            </form>
        </main>
        <footer>
            <p>¿Ya tienes una cuenta? <a href="./inicio.html">Inicia Sesión</a></p>
        </footer>
    </div>
</body>
</html>
