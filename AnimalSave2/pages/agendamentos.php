  <?php
  // Inclua a conexão com o banco de dados
  include('../config/config.php');

  // Verifica se o usuário está logado
  session_start();
  if (!isset($_SESSION['id_cliente'])) {
      header('Location: ../pages/login.php?error=1');
      exit();
  }

  // Pega o ID do cliente da sessão
  $id_cliente = $_SESSION['id_cliente'];

  // Buscar animais do cliente
  $stmt_animais = $pdo->prepare("SELECT * FROM animais WHERE id_cliente = ?");
  $stmt_animais->execute([$id_cliente]);
  $animais = $stmt_animais->fetchAll(PDO::FETCH_ASSOC);

  // Buscar serviços disponíveis (com valores predefinidos)
  $servicos = [
      ['id_servico' => 1, 'nome' => 'Banho', 'preco' => 50.00],
      ['id_servico' => 2, 'nome' => 'Tosa', 'preco' => 40.00],
      ['id_servico' => 3, 'nome' => 'Banho com Tosa', 'preco' => 90.00]
  ];

  // Lidar com o agendamento
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      if (isset($_POST['acao']) && $_POST['acao'] == 'agendar') {
          $id_animal = $_POST['id_animal'];
          $id_servico = $_POST['id_servico'];
          $data_hora = $_POST['data_hora'];
          $observacoes = $_POST['observacoes'];
          $valor = $_POST['valor'];

          // Verificar se a data e hora são válidas (no backend)
          $data = new DateTime($data_hora);
          $dia_da_semana = $data->format('w'); // 0 - Domingo, 1 - Segunda, ..., 6 - Sábado
          $hora = $data->format('H');

          // Verificar se o dia é Domingo (não permitido)
          if ($dia_da_semana == 0) {
              echo "Domingo não é permitido para agendamento.";
              exit();
          }

          // Verificar se é horário válido
          if (($dia_da_semana >= 1 && $dia_da_semana <= 5 && ($hora < 8 || $hora > 18)) || 
              ($dia_da_semana == 6 && ($hora < 9 || $hora > 15))) {
              echo "Escolha um horário dentro do expediente.";
              exit();
          }

          // Inserir agendamento no banco
          $stmt_agendamento = $pdo->prepare("INSERT INTO Agendamentos (id_animal, data_hora, id_servico, status, valor, observacoes) 
                                              VALUES (?, ?, ?, 'confirmado', ?, ?)");
          $stmt_agendamento->execute([$id_animal, $data_hora, $id_servico, $valor, $observacoes]);

          // Redirecionar ou mostrar uma mensagem de sucesso
          header('Location: agendamentos.php');
          exit();
      }
      // Definir cabeçalhos para evitar cache no navegador
header("Cache-Control: no-cache, no-store, must-revalidate");  // Para evitar cache
header("Pragma: no-cache");  // Para versões mais antigas de HTTP
header("Expires: 0");  // Para garantir que o conteúdo expira imediatamente
  }
  ?>
  <!DOCTYPE html>
  <html lang="pt-br">
  <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Agendamento</title>
      <link rel="stylesheet" href="../assets/css/paginas/agendamento.css?=v2">
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
      <style>
          /* Estilo para o botão flutuante */
          #btn-cadastrar-pet {
              position: fixed;
              bottom: 30px;
              right: 30px;
              z-index: 1000;
          }
      </style>
  </head>
  <body>

  <!-- Barra de navegação -->
  <nav class="navbar navbar-expand-lg" id="navbar-top">
      <div class="container-fluid">
          <a href=""><img src="../assets/img/ícones/logo.png" alt="logo" class="logo" style="border-radius: 50%; width: 100px"></a>
          <a class="navbar-brand" href="#"></a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
              <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                  <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
                  <li class="nav-item"><a class="nav-link" href="#">Sobre</a></li>
                  <li class="nav-item"><a class="nav-link" href="#">Serviços</a></li>
                  <li class="nav-item"><a class="nav-link" href="#">Depoimentos</a></li>
              </ul>

              <!-- Menu de Agendamentos -->
              <div class="login-box">
                  <?php if (isset($_SESSION['id_cliente'])): ?>
                      <div class="dropdown">
                          <button class="btn btn-link dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                              <?php echo htmlspecialchars($_SESSION['nome']); ?>
                          </button>
                          <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                              <li><a class="dropdown-item" href="perfil.php">Perfil</a></li>
                              <li><a class="dropdown-item" href="../actions/logout.php">Sair</a></li>
                          </ul>
                      </div>
                  <?php else: ?>
                      <a href="login.php"><button type="submit">Entrar</button></a>
                      <a href="registrar.php"><button type="submit">Registrar</button></a>
                  <?php endif; ?>
              </div>
          </div>
      </div>
  </nav>

  <!-- Conteúdo do agendamento -->
  <main>
      <div class="container py-5">
          <h1>Agendar um Serviço</h1>

          <!-- Informativo sobre os horários permitidos -->
          <div class="alert alert-info" role="alert">
              <h4 class="alert-heading">Horários de Agendamento:</h4>
              <p><strong>Segunda a Sexta:</strong> 08:00 - 18:00</p>
              <p><strong>Sábado:</strong> 09:00 - 15:00</p>
              <p><strong>Domingo:</strong> Fechado</p>
              <hr>
              <p class="mb-0">Por favor, escolha um horário dentro desses períodos ao fazer seu agendamento.</p>
          </div>

          <form action="agendamento.php" method="POST">
              <div class="mb-3">
                  <label for="id_animal" class="form-label">Escolha seu Animal</label>
                  <select name="id_animal" id="id_animal" class="form-select" required>
                      <option value="">Selecione o Animal</option>
                      <?php foreach ($animais as $animal): ?>
                          <option value="<?php echo $animal['id_animal']; ?>"><?php echo htmlspecialchars($animal['nome']); ?> - <?php echo htmlspecialchars($animal['tipo']); ?></option>
                      <?php endforeach; ?>
                  </select>
              </div>

              <div class="mb-3">
                  <label for="id_servico" class="form-label">Escolha o Serviço</label>
                  <select name="id_servico" id="id_servico" class="form-select" required>
                      <option value="">Selecione o Serviço</option>
                      <?php foreach ($servicos as $servico): ?>
                          <option value="<?php echo $servico['id_servico']; ?>" data-preco="<?php echo $servico['preco']; ?>">
                              <?php echo htmlspecialchars($servico['nome']); ?> - R$ <?php echo number_format($servico['preco'], 2, ',', '.'); ?>
                          </option>
                      <?php endforeach; ?>
                  </select>
              </div>

              <div class="mb-3">
                  <label for="data_hora" class="form-label">Data e Hora</label>
                  <input type="datetime-local" name="data_hora" id="data_hora" class="form-control" required>
              </div>

              <div class="mb-3">
                  <label for="observacoes" class="form-label">Observações</label>
                  <textarea name="observacoes" id="observacoes" class="form-control" rows="4"></textarea>
              </div>

              <div class="mb-3">
                  <label for="valor" class="form-label">Valor</label>
                  <input type="text" name="valor" id="valor" class="form-control" placeholder="Valor do serviço" readonly>
              </div>

              <button type="submit" class="btn btn-primary" name="acao" value="agendar">Confirmar Agendamento</button>
          </form>
      </div>
  </main>

  <!-- Botão flutuante para Cadastrar Pet -->
  <button id="btn-cadastrar-pet" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalCadastrarPet">
      Cadastrar Pet
  </button>

  <!-- Modal de Cadastrar Pet -->
  <div class="modal fade" id="modalCadastrarPet" tabindex="-1" aria-labelledby="modalCadastrarPetLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalCadastrarPetLabel">Cadastrar Novo Pet</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="agendamentos.php" method="POST">
              <input type="hidden" name="acao" value="cadastrar_pet">
              <div class="mb-3">
                  <label for="nome_animal" class="form-label">Nome do Animal</label>
                  <input type="text" class="form-control" name="nome_animal" required>
              </div>
              <div class="mb-3">
                  <label for="tipo_animal" class="form-label">Tipo de Animal</label>
                  <select name="tipo_animal" class="form-control">
                      <option value="Cachorro">Cachorro</option>
                      <option value="Gato">Gato</option>
                  </select>
              </div>
              <div class="mb-3">
                  <label for="raca_animal" class="form-label">Raça</label>
                  <input type="text" class="form-control" name="raca_animal" required>
              </div>
              <div class="mb-3">
                  <label for="idade_animal" class="form-label">Idade</label>
                  <input type="number" class="form-control" name="idade_animal" required>
              </div>
              <div class="mb-3">
                  <label for="peso_animal" class="form-label">Peso</label>
                  <input type="number" class="form-control" name="peso_animal" step="0.1" required>
              </div>
              <button type="submit" class="btn btn-primary">Cadastrar</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <footer class="roda-pe" id="roda-pe">
    <div class="roda-pe-container">
      <div class="roda-pe-localizacao">
        <h5>Localização</h5>
        <p>Rua Exemplo, 123 - Bairro Feliz, Cidade - Estado</p>
        <p><strong>Telefone:</strong> (11) 12345-6789</p>
      </div>
      <div class="roda-pe-descricao">
        <h5>Sobre AnimalSave</h5>
        <p>AnimalSave oferece serviços de banho e tosa para pets com todo o carinho, cuidado e qualidade. Garantimos uma experiência segura e confortável para o seu melhor amigo!</p>
      </div>
      <div class="roda-pe-horario">
        <h5>Horário de Funcionamento</h5>
        <p>Segunda a Sexta: 08h - 18h</p>
        <p>Sábado: 09h - 15h</p>
        <p>Domingo: Fechado</p>
      </div>
      <div class="roda-pe-maps">
        <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d403.43218990107175!2d-48.1191905315842!3d-15.850528612151736!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1spt-PT!2sbr!4v1747960968258!5m2!1spt-PT!2sbr" width="280" height="180" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
      </div>
    </div>
    <div class="roda-pe-footer">
      <p>&copy; 2025 AnimalSave - Todos os direitos reservados</p>
    </div>
  </footer>

  <!-- Scripts do Bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Script para calcular o valor -->
  <script>
      // Atualiza o valor do serviço ao selecionar um serviço
      document.getElementById('id_servico').addEventListener('change', function() {
          var selectedOption = this.options[this.selectedIndex];
          var preco = selectedOption.getAttribute('data-preco');

          // Atualiza o valor no campo
          document.getElementById('valor').value = 'R$ ' + parseFloat(preco).toFixed(2).replace('.', ',');
      });

      // Função de validação de data e hora
      document.getElementById('data_hora').addEventListener('change', function() {
          var selectedDate = new Date(this.value);
          var day = selectedDate.getDay(); // 0 (Domingo) até 6 (Sábado)
          var hour = selectedDate.getHours();
          
          // Validação: se for domingo
          if (day === 0) {
              alert('Domingo não é permitido para agendamento.');
              this.value = ''; // Limpar campo
          }
          
          // Validação: horário fora de expediente
          if ((day >= 1 && day <= 5 && (hour < 8 || hour > 18)) || (day === 6 && (hour < 9 || hour > 15))) {
              alert('Escolha um horário dentro do expediente.');
              this.value = ''; // Limpar campo
          }
      });
  </script>


  </body>
  </html>
