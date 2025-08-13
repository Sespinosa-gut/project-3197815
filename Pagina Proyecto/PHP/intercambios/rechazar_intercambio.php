<?php
require_once "../conexion.php";
require_once "../verificar_sesion.php";
require_once "../seguridad/csrf.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    csrf_verify_or_die();

    $id = intval($_POST['id'] ?? 0);
    $sql = "UPDATE intercambios i
            JOIN productos p ON p.id = i.producto_objetivo_id
            SET i.estado = 'rechazado', i.actualizado_en = NOW()
            WHERE i.id = ? AND p.usuario_id = ? AND i.estado = 'pendiente'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id, $_SESSION['id']);
    if ($stmt->execute() && $stmt->affected_rows === 1) {
        header("Location: ../gestionar-intercambios.html?rechazado=1");
    } else {
        http_response_code(400); echo "No autorizado o ya procesado.";
    }
}
