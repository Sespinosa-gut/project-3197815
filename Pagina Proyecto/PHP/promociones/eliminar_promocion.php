<?php
require_once "../conexion.php";
require_once "../verificar_sesion.php";
require_once "../seguridad/csrf.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    csrf_verify_or_die();
    $id = intval($_POST['id'] ?? 0);

    if (($_SESSION['rol'] ?? 'usuario') !== 'admin') {
        $sql = "DELETE pr FROM promociones pr
                JOIN productos p ON p.id = pr.producto_id
                WHERE pr.id = ? AND p.usuario_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $id, $_SESSION['id']);
    } else {
        $sql = "DELETE FROM promociones WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
    }

    if ($stmt->execute() && $stmt->affected_rows === 1) {
        header("Location: ../gestionar-promociones.html?eliminado=1");
    } else {
        http_response_code(400); echo "No autorizado o inexistente.";
    }
}
