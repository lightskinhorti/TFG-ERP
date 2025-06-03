<?php
// Función para iniciar sesión segura y proteger rutas privadas
function requireLogin() {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    // Si no hay usuario en sesión, redirige al login
    if (!isset($_SESSION['user'])) {
        header('Location: index.php?controller=auth&action=login');
        exit;
    }

    return $_SESSION['user'];
}
function requireAdmin() {
    requireLogin();
    if ($_SESSION['user']['role'] !== 'admin') {
        header('Location: index.php?controller=dashboard');
        exit;
    }
}

