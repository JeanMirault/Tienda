<?php
session_start(); // Iniciar la sesión al comienzo del archivo

// Incluir archivo de conexión
include 'db_connection.php';

// Añadir producto al carrito
if (isset($_POST['add_to_cart'])) {
    $product_id = intval($_POST['product_id']); // Sanitizar el ID del producto
    
    // Verificar si el carrito ya existe en la sesión
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    
    // Añadir el producto al carrito
    if (!in_array($product_id, $_SESSION['cart'])) {
        $_SESSION['cart'][] = $product_id;
    }
}

// Consultar productos
$sql = "SELECT codigo, nombre, detalle, imagen, precio FROM Productos";
$result = $conn->query($sql);
?>

<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vintage Store</title>
    <link rel="stylesheet" href="CSS/styles.css">

</head>

<body>

    <header>

        <div class="navbar">

            <h1>Vintage Store</h1>

            <div class="navbar-links">

                <a href="cart.php">Ver Carrito</a>
                <a href="contact.php">Contacto</a>                  

            </div>

        </div>

    </header>

    <main>
        
        <h2>Productos</h2>

        <div class="gallery">

            

            <?php
            if ($result->num_rows > 0) {

                // Mostrar cada producto
                while ($row = $result->fetch_assoc()) {

                    echo "<div class='product'>";
                    echo "<img src='" . htmlspecialchars($row["imagen"]) . "' alt='" . htmlspecialchars($row["nombre"]) . "'>";
                    echo "<h2>" . htmlspecialchars($row["nombre"]) . "</h2>";
                    echo "<p>" . htmlspecialchars($row["detalle"]) . "</p>";
                    echo "<p><strong>Precio: $" . htmlspecialchars($row["precio"]) . "</strong></p>";
                    echo "<form method='post' action='index.php'>";
                    echo "<input type='hidden' name='product_id' value='" . htmlspecialchars($row["codigo"]) . "'>";
                    echo "<input type='submit' name='add_to_cart' value='Añadir al carrito'>";
                    echo "</form>";
                    echo "</div>";

                }

            } else {

                echo "No hay productos disponibles.";

            }

            ?>

        </div>

    </main>

    <footer>

        <p>&copy; <?php echo date("Y"); ?> Vintage Store. Todos los derechos reservados.</p>
        <p><a href="#">Política de Privacidad</a> | <a href="#">Términos y Condiciones</a></p>

    </footer>

</body>

</html>

<?php

// Cerrar la conexión
$conn->close();

?>
