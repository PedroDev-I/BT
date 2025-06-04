<?php
session_start(); // Inicia a sessão

// Destrói todas as variáveis da sessão
session_unset();

// Destrói a sessão
session_destroy();

// Redireciona o usuário para a página de login
header("Location: ../pages/login_adm.php");
exit;
?>