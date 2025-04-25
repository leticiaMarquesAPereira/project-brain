<?php
session_start();

function verificarAcesso(array $tiposPermitidos)
{
    $tipoUsuario = $_SESSION['usuario']['tipo_usuario'];

    if (!in_array($tipoUsuario, $tiposPermitidos)) {
        echo "Acesso negado. Você não tem permissão para acessar esta página.";
        header("Location: dashboard.php");
        exit();
    }
}