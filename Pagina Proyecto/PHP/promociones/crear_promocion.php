<?php
require_once "../conexion.php";
require_once "../funciones.php";
require_once "../verificar_sesion.php";
require_once "../seguridad/csrf.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    csrf_verify_or_die();

    $producto_id = intval($_POST['producto_id'] ?? 0);
    $tipo = limpiarEntrada($_POST['tipo'] ?? 'descuento'); // descuento | 2x1 | envío_gratis ...
    $valor = floatval($_POST['valor'] ?? 0);
    $desde = $_POST['desde'] ?? null;
    $hasta = $_POST['hasta'] ?? null;

    // asegurar que el producto pertenece al usuario (o que sea admin)
    if (($_SESSION['rol'] ?? 'usuario') !== 'admin') {
        $chk = $conn->prepare("SELECT id FROM productos WHERE id=? AND usuario_id=?");
        $chk->bind_param("ii", $producto_id, $_SESSION['id']);
        $chk->execute();
        if (!$chk->get_result()->num_rows) { http_response_code(403); echo "No autorizado"; exit; }
    }

    $sql = "INSERT INTO promociones (producto_id, tipo, valor, desde, hasta, creado_en) VALUES (?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isdss", $producto_id, $tipo, $valor, $desde, $hasta);
    if ($stmt->execute()) {
        header("Location: ../gestionar-promociones.html?ok=1");
    } else {
        http_response_code(500); echo "Error: ".$stmt->error;
    }
}
