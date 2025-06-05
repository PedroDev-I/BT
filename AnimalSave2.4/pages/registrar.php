<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Página de Cadastro</title>
  <!-- Link para o CSS do Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <link rel="stylesheet" href="../assets/css/paginas/registrar.css">
  <style>
    /* Estilo para inputs com erro (vermelho) */
    .input-error {
      border: 2px solid red;
    }

    /* Barra de progresso */
    #password-strength-bar {
      height: 10px;
      border-radius: 5px;
      margin-top: 10px;
    }
  </style>
  <script>    // Função para verificar se as senhas são iguais
    function validarSenhas() {
      var senha = document.getElementById("password").value;
      var confirmSenha = document.getElementById("confirm-password").value;
      var alertContainer = document.getElementById("alert-container");
      var passwordInput = document.getElementById("password");
      var confirmPasswordInput = document.getElementById("confirm-password");
      
      // Verifica se as senhas são diferentes
      if (senha !== confirmSenha) {
        alertContainer.innerHTML = '<div class="alert alert-danger" role="alert">As senhas não são iguais. Por favor, tente novamente.</div>';
        alertContainer.style.display = "block"; // Exibe o alerta
        passwordInput.classList.add("input-error"); // Adiciona a classe de erro ao input
        confirmPasswordInput.classList.add("input-error"); // Adiciona a classe de erro ao input
        passwordInput.value = ''; // Apaga o valor do campo de senha
        return false; // Impede o envio do formulário
      }

      alertContainer.style.display = "none"; // Esconde o alerta
      passwordInput.classList.remove("input-error"); // Remove a classe de erro se a senha estiver correta
      confirmPasswordInput.classList.remove("input-error"); // Remove a classe de erro se as senhas coincidirem
      return true; // Permite o envio do formulário
    }

    // Função para validar a força da senha e mostrar uma barra de progresso
    function validarForcaSenha() {
      var senha = document.getElementById("password").value;
      var strengthBar = document.getElementById("password-strength-bar");

      var score = 0;

      // Verifica a presença de diferentes tipos de caracteres
      if (senha.length >= 8) score++; // Mínimo de 8 caracteres
      if (/[0-9]/.test(senha)) score++; // Número
      if (/[a-z]/.test(senha)) score++; // Letra minúscula
      if (/[A-Z]/.test(senha)) score++; // Letra maiúscula
      if (/[!@#$%^&*(),.?":{}|<>]/.test(senha)) score++; // Caractere especial

      // Calcula a porcentagem de força da senha
      var strengthPercentage = score * 20; // Cada critério vale 20 pontos

      // Aplica a barra de progresso com base na força da senha
      strengthBar.style.width = strengthPercentage + "%";

      // Define a cor da barra de acordo com a força da senha
      if (strengthPercentage < 40) {
        strengthBar.classList.remove("bg-warning", "bg-success");
        strengthBar.classList.add("bg-danger");
      } else if (strengthPercentage < 80) {
        strengthBar.classList.remove("bg-danger", "bg-success");
        strengthBar.classList.add("bg-warning");
      } else {
        strengthBar.classList.remove("bg-danger", "bg-warning");
        strengthBar.classList.add("bg-success");
      }
    }</script>
</head>
<body>

  <div class="login-container">
    <div class="login-box">
      <h2>Cadastro</h2>

      <!-- Container para o alerta -->
      <div id="alert-container" style="display: none;"></div>

      <!-- Formulário de cadastro com verificação de senhas -->
      <form action="../actions/action_registrar.php" method="POST" onsubmit="return validarSenhas()">
        <div class="input-group">
          <label for="name">Nome Completo</label>
          <input type="text" id="name" name="name" placeholder="Digite seu nome completo" required>
        </div>

        <div class="input-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" placeholder="Digite seu email" required>
        </div>

        <div class="input-group">
          <label for="password">Senha</label>
          <input type="password" id="password" name="password" placeholder="Digite sua senha" required onkeyup="validarForcaSenha()">
          
          <!-- Barra de força da senha -->
          <div id="password-strength-bar" class="progress-bar" role="progressbar" style="width: 0;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
        </div>

        <div class="input-group">
          <label for="confirm-password">Confirmar Senha</label>
          <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirme sua senha" required>
        </div>

        <div class="terms">
  <input type="checkbox" id="terms" name="terms" required>
  <label for="terms">Eu li e concordo com os <a href="termos.html" target="_blank">termos de uso</a></label>
</div>

        <button type="submit" class="btn btn-primary">Cadastrar</button>

        <div class="additional-links">
          <p>Já tem uma conta? <a href="login.php">Faça login</a></p>
        </div>
      </form>
    </div>
  </div>

  <!-- Script do Bootstrap (opcional, mas recomendado para alguns componentes) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
