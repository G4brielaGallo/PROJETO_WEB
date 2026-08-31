<?php
require_once 'auth.php';

if (estaLogado()) {
    registrarLog($pdo, $_SESSION['username'], 'Logout realizado');
}

$_SESSION = [];
session_destroy();

header('Location: login.php');
exit;
