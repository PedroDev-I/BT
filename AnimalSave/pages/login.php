<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Página de Login</title>
  <!-- Link para o CSS -->
  <link rel="stylesheet" href="../assets/css/paginas/login.css">
</head>
<body>

  <div class="login-container">
    <div class="login-box">
      <h2>Login</h2>

      <!-- Formulário de login -->
      <form action="#" method="POST">
        <div class="input-group">
          <label for="username">Usuário</label>
          <input type="text" id="username" name="username" placeholder="Digite seu usuário" required>
        </div>

        <div class="input-group">
          <label for="password">Senha</label>
          <input type="password" id="password" name="password" placeholder="Digite sua senha" required>
        </div>

        <div class="remember-me">
          <input type="checkbox" id="remember" name="remember">
          <label for="remember">Lembrar-me</label>
        </div>

        <button type="submit" class="btn">Entrar</button>

        <div class="additional-links">
          <a href="#">Esqueceu sua senha?</a>
          <p>Ainda não tem uma conta? <a href="registrar.php">Cadastre-se</a></p>
        </div>
      </form>
    </div>
  </div>

</body>
</html>
