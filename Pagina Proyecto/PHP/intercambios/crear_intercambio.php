<?php
require_once "../conexion.php";
require_once "../funciones.php";
require_once "../verificar_sesion.php";
require_once "../seguridad/csrf.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    csrf_verify_or_die();

    $producto_oferta_id = intval($_POST['producto_oferta_id'] ?? 0);   // producto que ofrezco
    $producto_objetivo_id = intval($_POST['producto_objetivo_id'] ?? 0); // producto que quiero
    $mensaje = limpiarEntrada($_POST['mensaje'] ?? '');

    // Solo si ambos ids > 0
    if ($producto_oferta_id <= 0 || $producto_objetivo_id <= 0) {
        http_response_code(400); echo "Datos inválidos"; exit;
    }

    $sql = "INSERT INTO intercambios (usuario_solicita_id, producto_oferta_id, producto_objetivo_id, mensaje, estado, creado_en)
            VALUES (?, ?, ?, ?, 'pendiente', NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiis", $_SESSION['id'], $producto_oferta_id, $producto_objetivo_id, $mensaje);
    if ($stmt->execute()) {
        header("Location: ../gestionar-intercambios.html?creado=1");
    } else {
        http_response_code(500); echo "Error: ".$stmt->error;
    }
}
