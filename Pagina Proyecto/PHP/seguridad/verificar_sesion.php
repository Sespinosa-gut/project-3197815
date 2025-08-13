<?php
// Reemplaza tu verificar_sesion.php por esto (o amplíalo)
ini_set('session.cookie_httponly', '1');
ini_set('session.use_strict_mode', '1');
ini_set('session.cookie_secure', isset($_SERVER['HTTPS']) ? '1' : '0');
session_name('swap_it_sess');

session_start();
if (!isset($_SESSION['id'])) {
    header("Location: ../iniciosesion.html");
    exit();
}
if (!isset($_SESSION['ip'])) {
    $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'] ?? '';
    $_SESSION['ua'] = $_SERVER['HTTP_USER_AGENT'] ?? '';
} else {
    // Mitigar fijación de sesión
    if (($_SESSION['ip'] ?? '') !== ($_SERVER['REMOTE_ADDR'] ?? '')
        || ($_SESSION['ua'] ?? '') !== ($_SERVER['HTTP_USER_AGENT'] ?? '')) {
        session_unset(); session_destroy();
        header("Location: ../iniciosesion.html"); exit();
    }
}
session_regenerate_id(true);
