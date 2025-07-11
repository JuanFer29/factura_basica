<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura Básica</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">

<h2>Formulario de Factura</h2>
<form method="POST">
    <h4>Datos del Cliente</h4>
    <div class="mb-3">
        <label>Nombre del cliente:</label>
        <input type="text" name="cliente" class="form-control">
    </div>
    <div class="mb-3">
        <label>Correo electrónico:</label>
        <input type="email" name="email" class="form-control">
    </div>
    <div class="mb-3">
        <label>Fecha:</label>
        <input type="date" name="fecha" class="form-control">
    </div>
    <div class="mb-3">
        <label>Comentarios:</label>
        <textarea name="comentarios" class="form-control"></textarea>
    </div>

    <h4>Productos</h4>
    <?php for ($i = 0; $i < 3; $i++) { ?>
        <div class="border p-3 mb-2">
            <label>Nombre del producto:</label>
            <input type="text" name="producto[]" class="form-control mb-2">

            <label>Precio:</label>
            <input type="number" name="precio[]" class="form-control mb-2">

            <label>Cantidad:</label>
            <input type="number" name="cantidad[]" class="form-control mb-2">

            <label>Categoría:</label>
            <select name="categoria[]" class="form-control mb-2">
                <option value="tecnologia">Tecnología</option>
                <option value="hogar">Hogar</option>
                <option>Otros</option>
            </select>

            <label>
                <input type="checkbox" name="iva[<?php echo $i; ?>]"> Aplicar IVA (15%)
            </label>
        </div>
    <?php } ?>

    <button type="submit" class="btn btn-primary">Generar Factura</button>
</form>

<?php
// Revisar si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cliente = $_POST["cliente"];
    $email = $_POST["email"];
    $fecha = $_POST["fecha"];
    $comentarios = $_POST["comentarios"];

    $productos = $_POST["producto"];
    $precios = $_POST["precio"];
    $cantidades = $_POST["cantidad"];
    $categorias = $_POST["categoria"];
    $iva = isset($_POST["iva"]) ? $_POST["iva"] : [];

    // función para el total
    function calcularTotalConIVA($precio, $cantidad, $aplicaIVA) {
        $sub = $precio * $cantidad;
        $iva = $aplicaIVA ? $sub * 0.15 : 0;
        $total = $sub + $iva;
        return [$sub, $iva, $total];
    }

    echo "<hr>";
    echo "<h3>Factura Generada</h3>";
    echo "<p><b>Cliente:</b> $cliente</p>";
    echo "<p><b>Email:</b> $email</p>";
    echo "<p><b>Fecha:</b> $fecha</p>";
    echo "<p><b>Comentarios:</b> $comentarios</p>";

    echo "<table class='table table-striped'>";
    echo "<thead><tr><th>Producto</th><th>Precio</th><th>Cantidad</th><th>Subtotal</th><th>IVA</th><th>Total</th></tr></thead><tbody>";

    $subTotalG = 0;
    $ivaTotal = 0;

    for ($i = 0; $i < count($productos); $i++) {
        $p = $productos[$i];
        $pr = $precios[$i];
        $cant = $cantidades[$i];
        $aplica = isset($iva[$i]);

        list($sub, $iv, $tot) = calcularTotalConIVA($pr, $cant, $aplica);

        echo "<tr>";
        echo "<td>$p</td>";
        echo "<td>$" . number_format($pr, 2) . "</td>";
        echo "<td>$cant</td>";
        echo "<td>$" . number_format($sub, 2) . "</td>";
        echo "<td>$" . number_format($iv, 2) . "</td>";
        echo "<td>$" . number_format($tot, 2) . "</td>";
        echo "</tr>";

        $subTotalG += $sub;
        $ivaTotal += $iv;
    }

    $totalPagar = $subTotalG + $ivaTotal;

    echo "</tbody></table>";

    echo "<p><strong>Subtotal General:</strong> $" . number_format($subTotalG, 2) . "</p>";
    echo "<p><strong>Total IVA:</strong> $" . number_format($ivaTotal, 2) . "</p>";
    echo "<p><strong>Total a Pagar:</strong> $" . number_format($totalPagar, 2) . "</p>";
}
?>

</body>
</html>
