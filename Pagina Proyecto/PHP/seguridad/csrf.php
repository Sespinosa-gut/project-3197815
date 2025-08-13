<?php
// CSRF helpers
if (session_status() === PHP_SESSION_NONE) session_start();

function csrf_init() {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = [
            'token' => bin2hex(random_bytes(32)),
            'expira' => time() + 3600 // 1 hora
        ];
    } elseif (time() > $_SESSION['csrf']['expira']) {
        $_SESSION['csrf']['token'] = bin2hex(random_bytes(32));
        $_SESSION['csrf']['expira'] = time() + 3600;
    }
    return $_SESSION['csrf']['token'];
}

function csrf_input() {
    $t = csrf_init();
    echo '<input type="hidden" name="csrf_token" value="'.htmlspecialchars($t, ENT_QUOTES, 'UTF-8').'">';
}

function csrf_verify_or_die() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') return; // solo valida en POST
    if (!isset($_POST['csrf_token'], $_SESSION['csrf']['token'])) die('CSRF inválido');
    if (!hash_equals($_SESSION['csrf']['token'], $_POST['csrf_token'])) die('CSRF inválido');
    if (time() > $_SESSION['csrf']['expira']) die('CSRF expirado');
}