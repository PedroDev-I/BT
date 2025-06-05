    // Função para verificar se as senhas são iguais
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
    }