<?php
// Inicia a sessão (se já não estiver iniciada)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Limpa todos os dados da sessão
$_SESSION = array();

// 2. Destrói a sessão
session_destroy();

// 3. Redireciona para a página de login
header("Location: ../view/login.php");
exit();