<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Página de Login</title>
  <link rel="stylesheet" href="../assets/css/paginas/login.css?=v1">
</head>
<body>

  <div class="login-container">
    <div class="login-box">
      <h2>Login</h2>

      <!-- Formulário de login -->
      <form action="../actions/action_login.php" method="POST">
        <div class="input-group">
          <label for="email">E-mail</label>
          <input type="email" id="email" name="email" placeholder="Digite seu e-mail" required>
        </div>

        <div class="input-group">
          <label for="senha">Senha</label>
          <input type="password" id="senha" name="senha" placeholder="Digite sua senha" required>
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

  <!-- Popup de erro de login -->
  <div class="popup" id="loginPopup">
    <div class="message">
      <h2>Você precisa estar logado para acessar esta página!</h2>
      <button onclick="closePopup()">Fechar</button>
    </div>
  </div>

  <script>
    // Verifica se o parâmetro 'error' está presente na URL
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('error')) {
      // Exibe o popup
      const popup = document.getElementById('loginPopup');
      popup.style.display = 'flex';
    }

    // Função para fechar o popup
    function closePopup() {
      const popup = document.getElementById('loginPopup');
      popup.style.display = 'none';
    }
  </script>

</body>
</html>
