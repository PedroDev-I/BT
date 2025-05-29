<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['id_cliente'])) {
    // Se não estiver logado, redireciona para a página de login com a mensagem de erro
    header('Location: ../pages/login.php?error=1');
    exit(); // Interrompe a execução do código
}
?>
<link rel="stylesheet" href="../assets/css/paginas/agendamento.css">

<div class="container">
  <h2>Cadastrar Pet</h2>
  
  <button id="toggleForm" class="btn">Cadastrar Pet</button>
  
  <form id="petForm" class="hidden">
    <div class="form-group">
      <label for="nomePet">Nome do Pet:</label>
      <input type="text" id="nomePet" placeholder="Digite o nome do pet">
    </div>
    
    <div class="form-group">
      <label for="especiePet">Espécie:</label>
      <input type="text" id="especiePet" placeholder="Ex.: Cachorro, Gato">
    </div>
    
    <div class="form-group">
    <label for="racaPet">Porte</label>
  <select id="racaPet">
    <option value="" disabled selected>Selecione o porte</option>
    <option value="labrador">Pequeno</option>
    <option value="poodle">Médio</option>
    <option value="bulldog">Grande</option>
    <!-- Adicione outras raças aqui -->
  </select>
    </div>
    
    <div class="form-group">
      <label for="idadePet">Idade:</label>
      <input type="number" id="idadePet" placeholder="Idade do pet">
    </div>
    
    <div class="form-group">
      <label for="fotoPet">Foto do Pet:</label>
      <input type="file" id="fotoPet">
    </div>
    
    <button type="submit" class="btn submit">Salvar</button>
  </form>
</div>


<script>
  const toggleBtn = document.getElementById('toggleForm');
  const form = document.getElementById('petForm');

  toggleBtn.addEventListener('click', () => {
    form.classList.toggle('hidden');
  });
</script>