<?php

session_start();
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = $conn->real_escape_string($_POST['nombre']);
    $telefono = $conn->real_escape_string($_POST['telefono']);
    $correo = $conn->real_escape_string($_POST['correo']);
    $detalle = $conn->real_escape_string($_POST['detalle']);

    $sql = "INSERT INTO Consultas (nombre, telefono, correo, detalle) VALUES ('$nombre', '$telefono', '$correo', '$detalle')";

    if ($conn->query($sql) === TRUE) {

        $mensaje = "Consulta enviada correctamente.";
        $mensaje_clase = "success";

    } else {

        error_log("Error al insertar datos: " . $conn->error, 3, "/var/log/apache2/error.log");
        $mensaje = "Hubo un error al enviar tu consulta. Por favor, intenta nuevamente.";
        $mensaje_clase = "error";

    }

    // Cerrar la conexión
    $conn->close();
}
?>

<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto - Vintage Store</title>
    <link rel="stylesheet" href="CSS/styles.css">

</head>

<body>

    <header>

        <div class="navbar">

            <h1>Vintage Store</h1>

            <div class="navbar-links">

                <a href="index.php">Volver a la tienda</a>
                <a href="cart.php">Ver Carrito</a>

            </div>

        </div>

    </header>

    <main>

        <h2>Formulario de Contacto</h2>

        <div class="contact-form-container">

            <form method="post" action="contact.php">

                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required>
                <label for="telefono">Teléfono:</label>
                <input type="tel" id="telefono" name="telefono">
                <label for="correo">Correo Electrónico:</label>
                <input type="email" id="correo" name="correo" required>
                <label for="detalle">Detalle de la consulta:</label>
                <textarea id="detalle" name="detalle" rows="5" required></textarea>
                <button type="submit">Enviar Consulta</button>

            </form>

            <?php if (isset($mensaje)): ?>

                <div class="form-message <?php echo $mensaje_clase; ?>">

                    <?php echo $mensaje; ?>

                </div>

            <?php endif; ?>

        </div>

    </main>

    <footer>

        <p>&copy; <?php echo date("Y"); ?> Vintage Store. Todos los derechos reservados.</p>
        <p><a href="#">Política de Privacidad</a> | <a href="#">Términos y Condiciones</a></p>

    </footer>

</body>

</html>
