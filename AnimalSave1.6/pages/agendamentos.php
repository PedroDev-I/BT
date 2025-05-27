<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['id_cliente'])) {
    // Se não estiver logado, redireciona para a página de login com a mensagem de erro
    header('Location: ../pages/login.php?error=1');
    exit(); // Interrompe a execução do código
}
?>
