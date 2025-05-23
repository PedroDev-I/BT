<?php

include('../config/config.php');

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Página de Cadastro</title>
  <!-- Link para o CSS -->
  <link rel="stylesheet" href="../assets/css/paginas/registrar.css">
</head>
<body>

  <div class="login-container">
    <div class="login-box">
      <h2>Cadastro</h2>

      <!-- Formulário de cadastro -->
      <form action="" method="POST">
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
          <input type="password" id="password" name="password" placeholder="Digite sua senha" required>
        </div>

        <div class="input-group">
          <label for="confirm-password">Confirmar Senha</label>
          <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirme sua senha" required>
        </div>

        <div class="terms">
          <input type="checkbox" id="terms" name="terms" required>
          <label for="terms">Eu li e concordo com os termos de uso</label>
        </div>

        <button type="submit" class="btn">Cadastrar</button>

        <div class="additional-links">
          <p>Já tem uma conta? <a href="login.php">Faça login</a></p>
        </div>
      </form>
    </div>
  </div>

</body>
</html>
