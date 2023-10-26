<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/inicio.css">
    <title>IAmOn - Inicio de Sesión</title>
</head>
<body>
    <div class="container">
        <header>
            <h1>IAmOn</h1>
            <p>Inicia sesión en tu cuenta</p>
        </header>
        <main>
            <form>
                <label for="username">Usuario:</label>
                <input type="text" id="username" name="username" required>

                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>

                <button type="submit">Iniciar Sesión</button>
            </form>
        </main>
        <footer>
            <p>¿No tienes una cuenta? <a href="./index.html">Regístrate</a></p>
            <p>Iniciar como modo invitado <a href="./switches.html">Continuar</a></p>
        </footer>
    </div>
</body>


</html>