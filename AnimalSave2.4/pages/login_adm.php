<?php
session_start();

// Verifica se o administrador já está logado
if (isset($_SESSION['id_funcionario'])) {
    // Se já estiver logado, redireciona para o painel de administração
    header('Location: admdashboard.php');
    exit;
}

// Verifica se a mensagem de erro existe
if (isset($_SESSION['mensagem'])) {
    echo '<div class="alert alert-danger">' . $_SESSION['mensagem'] . '</div>';
    unset($_SESSION['mensagem']); // Limpa a mensagem após exibi-la
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Admin</title>
    <link rel="stylesheet" href="../assets/css/paginas/login_adm.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h2>Login <br> Administrador</h2>
            <form action="../actions/action_login_adm.php" method="POST">
                <!-- Campo de Código -->
                <div class="input-group">
                    <label for="codigo">Código</label>
                    <input type="text" id="codigo" name="codigo" placeholder="Digite seu código" required>
                </div>

                <!-- Campo de Senha -->
                <div class="input-group">
                    <label for="password">Senha</label>
                    <input type="password" id="password" name="password" placeholder="Digite sua senha" required>
                </div>

                <!-- Botão de Login -->
                <button type="submit" class="btn">Entrar</button>
            </form>
        </div>
    </div>

</body>
</html>
