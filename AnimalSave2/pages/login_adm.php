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
            <form action="#" method="POST">
                <!-- Campo de Usuário -->
                <div class="input-group">
                    <label for="username">Usuário</label>
                    <input type="text" id="username" name="username" placeholder="Digite seu usuário" required>
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
