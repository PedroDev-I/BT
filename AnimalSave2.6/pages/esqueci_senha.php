<?php
// Inicia a sessão para verificar se o parâmetro 'cadastro' foi passado
session_start();

// Verifica se o parâmetro 'cadastro' está na URL, indicando que o cadastro foi feito com sucesso
if (isset($_GET['cadastro']) && $_GET['cadastro'] == 'sucesso') {
    $_SESSION['mensagem'] = 'Cadastro realizado com sucesso! Agora, faça login.';
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Página de Login</title>
  <link rel="stylesheet" href="../assets/css/paginas/login.css?=v1">
  <!-- Link do Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>

  <div class="login-container">
    <div class="login-box">
      <h2>Esqueci a senha</h2>

      <!-- Formulário de login -->
      <form action="../actions/action_login.php" method="POST">
        <div class="input-group">
          <label for="email">E-mail</label>
          <input type="email" id="email" name="email" placeholder="Digite seu e-mail" required>
        </div>
        <button type="submit" class="btn btn-primary">Enviar</button>
        </div>
      </form>
    </div>
  </div>

  <div class="popup" id="loginPopup">
    <div class="message">
      <h2>Você precisa estar logado para acessar esta página!</h2>
      <button onclick="closePopup()">Fechar</button>
    </div>
  </div>
  <!-- Script do Bootstrap (opcional, mas recomendado para alguns componentes) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

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
