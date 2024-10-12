<?php
session_start();
include 'db_connection.php';

// Finalizar compra
$compra_finalizada = false;
$mensaje_compra = '';
if (isset($_POST['finalize_purchase'])) {

    if (!empty($_SESSION['cart'])) {

        $ids = implode(',', array_map('intval', $_SESSION['cart']));
        $sql = "SELECT nombre, precio FROM Productos WHERE codigo IN ($ids)";
        $result = $conn->query($sql);
        $total = 0;

        $mensaje_compra .= "<h2>Resumen de Compra</h2>";
        $mensaje_compra .= "<ul>";

        while ($row = $result->fetch_assoc()) {

            $mensaje_compra .= "<li>" . htmlspecialchars($row["nombre"]) . " - $" . htmlspecialchars($row["precio"]) . "</li>";
            $total += $row["precio"];

        }

        $mensaje_compra .= "</ul>";
        $mensaje_compra .= "<p><strong>Total: $" . number_format($total, 2) . "</strong></p>";

        $compra_finalizada = true;  // Indicamos que la compra ha sido finalizada
        $_SESSION['cart'] = array(); // Vaciar el carrito después de la compra

    } else {

        $mensaje_compra = "<p>El carrito está vacío.</p>";

    }

}

// Vaciar carrito
if (isset($_POST['clear_cart'])) {

    $_SESSION['cart'] = array();

}

// Eliminar un producto del carrito
if (isset($_POST['remove_from_cart'])) {

    $product_id = intval($_POST['product_id']);

    if (($key = array_search($product_id, $_SESSION['cart'])) !== false) {

        unset($_SESSION['cart'][$key]);
        $_SESSION['cart'] = array_values($_SESSION['cart']);

    }

}

// Consultar productos en el carrito
$cart_items = array();
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {

    $ids = implode(',', array_map('intval', $_SESSION['cart']));
    $sql = "SELECT codigo, nombre, detalle, imagen, precio FROM Productos WHERE codigo IN ($ids)";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {

        while ($row = $result->fetch_assoc()) {

            $cart_items[] = $row;

        }

    }

}
?>

<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras - Vintage Store</title>
    <link rel="stylesheet" href="CSS/styles.css">

</head>

<body>

    <header>

        <div class="navbar">

            <h1>Vintage Store</h1>

            <div class="navbar-links">

                <a href="index.php">Volver a la tienda</a>
                <a href="contact.php">Contacto</a>                  

            </div>

        </div>

    </header>

    <main>

        <h2>Carrito de Compras</h2>

        <!-- Mostrar resumen de compra si se finalizó la compra -->
        <?php if ($compra_finalizada): ?>
            <div class="compra-finalizada">
                <?php echo $mensaje_compra; ?>
            </div>
        <?php endif; ?>

        <!-- Mostrar productos del carrito -->
        <div class="cart">

            <?php
            if (!empty($cart_items)) {

                foreach ($cart_items as $item) {
                    echo "<div class='product'>";
                    echo "<img src='" . htmlspecialchars($item["imagen"]) . "' alt='" . htmlspecialchars($item["nombre"]) . "'>";
                    echo "<h2>" . htmlspecialchars($item["nombre"]) . "</h2>";
                    echo "<p>" . htmlspecialchars($item["detalle"]) . "</p>";
                    echo "<p><strong>Precio: $" . htmlspecialchars($item["precio"]) . "</strong></p>";
                    echo "<form method='post' action='cart.php'>";
                    echo "<input type='hidden' name='product_id' value='" . htmlspecialchars($item["codigo"]) . "'>";
                    echo "<button type='submit' name='remove_from_cart'>Eliminar</button>";
                    echo "</form>";
                    echo "</div>";

                }

            } elseif (!$compra_finalizada) {

                echo "El carrito está vacío.";

            }
            ?>

        </div>

        <!-- Botones de acciones del carrito -->
        <?php if (!$compra_finalizada): ?>
            <form method="post" action="cart.php">
                <button type="submit" name="clear_cart">Vaciar carrito</button>
                <button type="submit" name="finalize_purchase">Finalizar compra</button>
            </form>
        <?php endif; ?>
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
