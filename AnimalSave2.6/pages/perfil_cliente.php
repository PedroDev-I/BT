<?php
// Inclua a conexão com o banco de dados
include('../config//config.php');

// Verifica se o usuário está logado
session_start();
if (!isset($_SESSION['id_cliente'])) {
    header('Location: ../pages/login.php?error=1');
    exit();
}

// Pega o ID do cliente da sessão
$id_cliente = $_SESSION['id_cliente'];

// Buscar dados do cliente no banco
$stmt = $pdo->prepare("SELECT * FROM Clientes WHERE id_cliente = ?");
$stmt->execute([$id_cliente]);
$cliente = $stmt->fetch(PDO::FETCH_ASSOC);

// Buscar animais do cliente
$stmt_animais = $pdo->prepare("SELECT * FROM Animais WHERE id_cliente = ?");
$stmt_animais->execute([$id_cliente]);
$animais = $stmt_animais->fetchAll(PDO::FETCH_ASSOC);

// Buscar agendamentos do cliente
$stmt_agendamentos = $pdo->prepare("SELECT * FROM Agendamentos WHERE id_animal IN (SELECT id_animal FROM Animais WHERE id_cliente = ?)");
$stmt_agendamentos->execute([$id_cliente]);
$agendamentos = $stmt_agendamentos->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['senha_antiga'], $_POST['nova_senha'])) {
    $senha_antiga = $_POST['senha_antiga'];
    $nova_senha = $_POST['nova_senha'];
}

// Processa troca de senha via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['senha_antiga'], $_POST['nova_senha'], $_POST['confirma_senha'])) {
    $senha_antiga = $_POST['senha_antiga'];
    $nova_senha = $_POST['nova_senha'];
    $confirma_senha = $_POST['confirma_senha'];

    // Buscar hash da senha atual
    $stmt = $pdo->prepare("SELECT senha FROM Clientes WHERE id_cliente = ?");
    $stmt->execute([$id_cliente]);
    $clienteSenha = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$clienteSenha || !password_verify($senha_antiga, $clienteSenha['senha'])) {
        $erro_senha = "Senha antiga incorreta.";
    } elseif ($nova_senha !== $confirma_senha) {
        $erro_senha = "Nova senha e confirmação não coincidem.";
    } else {
        // Atualiza a senha
        $nova_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
        $stmtUpdate = $pdo->prepare("UPDATE Clientes SET senha = ? WHERE id_cliente = ?");
        $stmtUpdate->execute([$nova_hash, $id_cliente]);

        $sucesso_senha = "Senha alterada com sucesso!";
    }
}
// Lidar com requisições POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['acao'])) {
        // Cadastro de novo pet
        if ($_POST['acao'] == 'cadastrar_pet') {
            $nome_animal = $_POST['nome_animal'];
            $tipo_animal = $_POST['tipo_animal'];
            $raca_animal = $_POST['raca_animal'];
            $idade_animal = $_POST['idade_animal'];
            $peso_animal = $_POST['peso_animal'];

            // Inserir no banco de dados
            $stmt_pet = $pdo->prepare("INSERT INTO Animais (id_cliente, nome, tipo, raca, idade, peso) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt_pet->execute([$id_cliente, $nome_animal, $tipo_animal, $raca_animal, $idade_animal, $peso_animal]);

            // Redirecionar após cadastro
            header('Location: perfil_cliente.php');
            exit();
        }
    }
}
// Atualizar endereço se o formulário for enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['endereco'])) {
    $novo_endereco = trim($_POST['endereco']);
    $stmt_update = $pdo->prepare("UPDATE Clientes SET endereco = ? WHERE id_cliente = ?");
    $stmt_update->execute([$novo_endereco, $id_cliente]);

    // Atualiza os dados na sessão, se necessário
    header("Location: perfil_cliente.php");
    exit();
}

// Processar o CPF
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cpf'])) {
  $novo_cpf = trim($_POST['cpf']);
  
  if (!empty($cliente['cpf']) && $cliente['cpf'] !== $novo_cpf) {
      $erro_cpf = "O CPF já foi registrado e não pode ser alterado.";
  } else {
      // Validar o CPF
      if (preg_match("/^[0-9]{11}$/", $novo_cpf)) {
          // Verificar se o CPF já existe no banco de dados
          $stmt_check_cpf = $pdo->prepare("SELECT id_cliente FROM Clientes WHERE cpf = ?");
          $stmt_check_cpf->execute([$novo_cpf]);
          $cpfExistente = $stmt_check_cpf->fetch(PDO::FETCH_ASSOC);

          if ($cpfExistente) {
              $erro_cpf = "CPF já cadastrado.";
          } else {
              // Atualiza o CPF no banco de dados
              $stmt_update_cpf = $pdo->prepare("UPDATE Clientes SET cpf = ? WHERE id_cliente = ?");
              $stmt_update_cpf->execute([$novo_cpf, $id_cliente]);

              // Atualiza a sessão (se necessário)
              $_SESSION['cpf'] = $novo_cpf;
              $sucesso_cpf = "CPF atualizado com sucesso!";

              // Redirecionar para evitar a repetição da ação ao voltar
              header("Location: perfil_cliente.php"); // Redireciona para a página de perfil
              exit();
          }
      } else {
          $erro_cpf = "CPF inválido. Certifique-se de que está no formato correto.";
      }
  }
}

// Processa atualização do CPF
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cpf'])) {
  $novo_cpf = trim($_POST['cpf']);
  
  // Verificar se o CPF já está salvo
  if (!empty($cliente['cpf'])) {
      // Se o CPF já foi salvo, não permite a alteração
      $erro_cpf = "O CPF já foi registrado e não pode ser alterado.";
  } else {
      // Verificar se o CPF já existe no banco de dados
      $stmt_check_cpf = $pdo->prepare("SELECT id_cliente FROM Clientes WHERE cpf = ?");
      $stmt_check_cpf->execute([$novo_cpf]);
      $cpfExistente = $stmt_check_cpf->fetch(PDO::FETCH_ASSOC);
      
      if ($cpfExistente) {
          // Se o CPF já existe, mostra o erro
          $erro_cpf = "CPF já cadastrado.";
      } else {
          // Atualiza o CPF no banco de dados
          $stmt_update_cpf = $pdo->prepare("UPDATE Clientes SET cpf = ? WHERE id_cliente = ?");
          $stmt_update_cpf->execute([$novo_cpf, $id_cliente]);

          // Atualiza a sessão (se necessário)
          $_SESSION['cpf'] = $novo_cpf;
          $sucesso_cpf = "CPF atualizado com sucesso!";
      }
  }
}

// Processar o upload de foto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['foto'])) {
  $foto = $_FILES['foto'];

  // Verificar se a imagem foi carregada corretamente
  if ($foto['error'] === UPLOAD_ERR_OK) {
      $extensao = pathinfo($foto['name'], PATHINFO_EXTENSION);
      $nomeFoto = "foto_" . $id_cliente . "." . $extensao;
      $caminhoDestino = "../assets/img/foto_cliente/" . $nomeFoto;

      // Mover o arquivo para o diretório
      if (move_uploaded_file($foto['tmp_name'], $caminhoDestino)) {
          // Atualizar o caminho da foto no banco de dados
          $stmt = $pdo->prepare("UPDATE Clientes SET foto = ? WHERE id_cliente = ?");
          $stmt->execute([$nomeFoto, $id_cliente]);

          // Atualizar a foto na sessão, se necessário
          $_SESSION['foto'] = $nomeFoto;

          // Redirecionar para evitar o resubmissão do formulário
          header("Location: perfil_cliente.php");
          exit();
      } else {
          $erro_foto = "Erro ao carregar a foto. Tente novamente.";
      }
  } else {
      $erro_foto = "Erro no upload da foto. Verifique o formato e tente novamente.";
  }
}

// Definir cabeçalhos para evitar cache no navegador
header("Cache-Control: no-cache, no-store, must-revalidate");  // Para evitar cache
header("Pragma: no-cache");  // Para versões mais antigas de HTTP
header("Expires: 0");  // Para garantir que o conteúdo expira imediatamente
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuário</title>
    
    <!-- Link para o CSS da página -->
    <link rel="stylesheet" href="../assets/css/paginas/perfil.css?=v2">

    <!-- Links para o Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
</head>
<body>

<!-- Barra de navegação -->
<nav class="navbar navbar-expand-lg" id="navbar-top">
  <div class="container-fluid">
    <a href="../index.php"><img src="../assets/img/ícones/logo.png" alt="logo" style="border-radius: 50%; width: 100px;"></a>
    <a class="navbar-brand" href="#"></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="../index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../index.php#sobre">Sobre</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../index.php#servicos">Serviços</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../index.php#depoimentos">Depoimentos</a>
        </li>
        
        <!-- Adicionando o item de Agendamentos se o usuário estiver logado -->
        <?php if (isset($_SESSION['id_cliente'])): ?>
          <li class="nav-item">
            <a class="nav-link" href="../pages/agendamentos.php">Agendamentos</a>
          </li>
        <?php endif; ?>

      </ul>
     <!-- Verifica se o usuário está logado -->
     <div class="login-box">
        <?php if (isset($_SESSION['id_cliente'])): ?>
          <!-- Menu Dropdown para o nome do cliente -->
          <div class="dropdown">
            <button class="btn btn-link dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false" style="background-color: white;">
              <?php echo $_SESSION['nome']; ?>
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
              <li><a class="dropdown-item" href="perfil_cliente.php">Perfil</a></li>
              <li><a class="dropdown-item" href="../actions/logout.php">Sair</a></li>
            </ul>
          </div>
        <?php else: ?>
          <a href="pages/login.php"><button type="submit">Entrar</button></a>
          <a href="pages/registrar.php"><button type="submit">Registrar</button></a>
        <?php endif; ?>
  </div>
</nav>

<!-- Conteúdo do perfil -->
<main>
    <div class="container py-5">
        <h1>Perfil de Usuário</h1>

        <!-- Seção de Informações do Usuário -->
          <div class="row">
              <div class="col-md-6">
                  <section>
                  <div>
    <label for="foto">Foto de Perfil:</label><br>
    <!-- Foto atual do cliente -->
    <img id="foto" 
     src="<?php echo !empty($cliente['foto']) ? '../assets/img/foto_cliente/' . htmlspecialchars($cliente['foto']) : 'https://via.placeholder.com/150'; ?>" 
     alt="Foto de Perfil" 
     class="foto-perfil" 
     style="cursor: pointer;" 
     onclick="document.getElementById('uploadFoto').click();">
    
    <!-- Formulário de upload de foto -->
    <form method="POST" enctype="multipart/form-data" id="formUploadFoto" style="display: none;">
        <input type="file" name="foto" id="uploadFoto" accept="image/*" onchange="this.form.submit();" style="display: none;">
    </form>
</div>
                      <div>
                          <label for="nome">Nome:</label>
                          <p id="nome"><?php echo htmlspecialchars($cliente['nome']); ?></p>
                      </div>
                      <div>
                      <div>
                    <div>
    <label for="cpf">CPF:</label>
    <p id="cpfTexto"><?php echo htmlspecialchars($cliente['cpf']); ?></p>
    <input type="text" name="cpf" id="cpfInput" class="form-control" style="display: none;" placeholder="Inserir CPF" value="<?php echo htmlspecialchars($cliente['cpf']); ?>" <?php echo !empty($cliente['cpf']) ? 'readonly' : ''; ?>>
    <div id="erroCpf" class="alert alert-danger" style="display: none;"></div>
</div>

<div class="mt-2">
    <button id="editarCpfBtn" class="btn btn-warning btn-sm" <?php echo !empty($cliente['cpf']) ? 'style="display: none;"' : ''; ?>>Editar</button>
    <button id="salvarCpfBtn" class="btn btn-success btn-sm" style="display: none;" onclick="showConfirmation()">Confirmar CPF</button>
</div>

<form method="POST" id="formCpf" style="display: none;">
    <input type="hidden" name="cpf" id="campoCpfFinal">
</form>

<div id="sucessoCpf" class="alert alert-success" style="display: none;">CPF atualizado com sucesso!</div>

                    <div>
                        <label for="email">Email:</label>
                        <p id="email"><?php echo htmlspecialchars($cliente['email']); ?></p>
                    </div>
                    <div>
    <label for="endereco">Endereço:</label>
    <p id="enderecoTexto"><?php echo htmlspecialchars($cliente['endereco']); ?></p>
    <input type="text" name="endereco" id="enderecoInput" class="form-control" style="display: none;" value="<?php echo htmlspecialchars($cliente['endereco']); ?>">
</div>

<div class="mt-2">
    <button id="editarEnderecoBtn" class="btn btn-warning btn-sm">Editar</button>
    <button id="salvarEnderecoBtn" class="btn btn-success btn-sm" style="display: none;">Salvar</button>
</div>

<form method="POST" id="formEndereco" style="display: none;">
    <input type="hidden" name="endereco" id="campoEnderecoFinal">
</form>


                    <!-- Botão para alterar senha -->
                    <!-- Botão para abrir modal de alterar senha -->
<button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#alterarSenhaModal">
    Alterar Senha
</button>

<!-- Modal -->
<div class="modal fade" id="alterarSenhaModal" tabindex="-1" aria-labelledby="alterarSenhaModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="alterarSenhaModalLabel">Alterar Senha</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
        <?php if (!empty($erro_senha)): ?>
          <div class="alert alert-danger"><?php echo htmlspecialchars($erro_senha); ?></div>
        <?php endif; ?>
        <?php if (!empty($sucesso_senha)): ?>
          <div class="alert alert-success"><?php echo htmlspecialchars($sucesso_senha); ?></div>
        <?php endif; ?>

        <div class="mb-3">
          <label for="senha_antiga" class="form-label">Senha Antiga</label>
          <input type="password" class="form-control" id="senha_antiga" name="senha_antiga" required>
        </div>
        <div class="mb-3">
          <label for="nova_senha" class="form-label">Nova Senha</label>
          <input type="password" class="form-control" id="nova_senha" name="nova_senha" required>
        </div>
        <div class="mb-3">
          <label for="confirma_senha" class="form-label">Confirmar Nova Senha</label>
          <input type="password" class="form-control" id="confirma_senha" name="confirma_senha" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary">Salvar</button>
      </div>
    </form>
  </div>
</div>
                </section>
            </div>

            <!-- Seção de Meus Pets -->
            <div class="col-md-6">
                <section>
                    <h2>Meus Pets</h2>
                    <!-- Tabela com os Pets cadastrados -->
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Espécie</th>
                                <th>Idade</th>
                                <th>Peso</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($animais as $animal): ?>
                            <tr>
                                <td><?php echo $animal['id_animal']; ?></td>
                                <td><?php echo htmlspecialchars($animal['nome']); ?></td>
                                <td><?php echo htmlspecialchars($animal['tipo']); ?></td>
                                <td><?php echo htmlspecialchars($animal['idade']); ?></td>
                                <td><?php echo htmlspecialchars($animal['peso']); ?></td>
                                <td><button class="btn btn-info">Alterar Informações</button></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
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
                </section>
            </div>
        </div>

        <!-- Seção de Meus Agendamentos -->
<div class="row">
    <div class="col-md-6">
        <section>
            <h2>Meus Agendamentos</h2>
            <!-- Tabela com os Agendamentos -->
            <table class="table">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Serviço</th>
                        <th>Status</th>
                        <th>Nota</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($agendamentos as $agendamento): ?>
                    <tr>
                        <td><?php echo $agendamento['id_agendamento']; ?></td>
                        <td>
                            <?php
                                // Buscar o nome do serviço baseado no ID do serviço
                                $stmt_servico = $pdo->prepare("SELECT nome FROM Servicos WHERE id_servico = ?");
                                $stmt_servico->execute([$agendamento['id_servico']]);
                                $servico = $stmt_servico->fetch(PDO::FETCH_ASSOC);
                                echo htmlspecialchars($servico['nome']);
                            ?>
                        </td>
                        <td><?php echo htmlspecialchars($agendamento['status']); ?></td>
                        <td>
                            <a href="nota.php?id_agendamento=<?= $agendamento['id_agendamento'] ?>" class="btn btn-info">
                                Ver Nota
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </div>
</div>
        </div>
    </div>
</main>

<!-- Scripts do Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.getElementById('editarEnderecoBtn').addEventListener('click', function () {
    document.getElementById('enderecoTexto').style.display = 'none';
    document.getElementById('enderecoInput').style.display = 'block';
    this.style.display = 'none';
    document.getElementById('salvarEnderecoBtn').style.display = 'inline-block';
});

document.getElementById('salvarEnderecoBtn').addEventListener('click', function () {
    const novoEndereco = document.getElementById('enderecoInput').value.trim();
    document.getElementById('campoEnderecoFinal').value = novoEndereco;
    document.getElementById('formEndereco').submit();
});
</script>
<script>
  let lastScrollTop = 0; // A posição da rolagem anterior

window.addEventListener("scroll", function() {
    let currentScroll = window.pageYOffset || document.documentElement.scrollTop;

    if (currentScroll > lastScrollTop) {
        // Se a rolagem for para baixo, esconde a navbar
        document.getElementById("navbar-top").classList.add("navbar-hidden");
    } else {
        // Se a rolagem for para cima, mostra a navbar
        document.getElementById("navbar-top").classList.remove("navbar-hidden");
    }

    lastScrollTop = currentScroll <= 0 ? 0 : currentScroll; // Evita que o valor negativo seja registrado
});

</script>
<script>document.getElementById('salvarCpfBtn').addEventListener('click', function () {
    const novoCpf = document.getElementById('cpfInput').value.trim();
    const erroCpf = document.getElementById('erroCpf');

    // Verificar se o CPF tem 11 dígitos
    if (novoCpf.length === 11 && /^[0-9]{11}$/.test(novoCpf)) {
        // Submete o formulário
        document.getElementById('campoCpfFinal').value = novoCpf;
        document.getElementById('formCpf').submit();
    } else {
        erroCpf.textContent = 'CPF inválido. Certifique-se de que está no formato correto.';
        erroCpf.style.display = 'block';
        document.getElementById('cpfInput').classList.add('is-invalid');
    }
});
</script>

<script>
// Exibir e ocultar os campos de CPF
document.getElementById('editarCpfBtn').addEventListener('click', function () {
    document.getElementById('cpfTexto').style.display = 'none';
    document.getElementById('cpfInput').style.display = 'block';
    this.style.display = 'none';
    document.getElementById('salvarCpfBtn').style.display = 'inline-block';
});

// Salvar CPF ao clicar no botão
document.getElementById('salvarCpfBtn').addEventListener('click', function () {
    const novoCpf = document.getElementById('cpfInput').value.trim();
    
    // Verificar se o CPF já existe
    const formCpf = document.getElementById('formCpf');
    const campoCpfFinal = document.getElementById('campoCpfFinal');
    campoCpfFinal.value = novoCpf;

    // Chamada AJAX ou validação no lado do cliente
    const cpfInput = document.getElementById('cpfInput');
    const erroCpf = document.getElementById('erroCpf');
    
    if (novoCpf.length === 11 && !erroCpf) {
        formCpf.submit(); // Submete o formulário
    } else {
        cpfInput.classList.add('is-invalid');
        erroCpf.style.display = 'block';
    }
});

// Função para simular a validação de CPF único no backend (dentro do PHP)
function checkCpfExistente(cpf) {
    fetch('../actions/check_cpf.php', {
        method: 'POST',
        body: JSON.stringify({ cpf: cpf }),
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.exists) {
            document.getElementById('cpfInput').classList.add('is-invalid');
            document.getElementById('erroCpf').innerText = 'CPF já cadastrado.';
        } else {
            document.getElementById('cpfInput').classList.remove('is-invalid');
            document.getElementById('erroCpf').style.display = 'none';
        }
    });
}
// Exemplo de como mostrar uma mensagem por 5 segundos
function exibirMensagem(mensagem, tipo) {
    // Criar um elemento de mensagem
    const divMensagem = document.createElement('div');
    divMensagem.classList.add('alert');
    divMensagem.classList.add(tipo); // 'alert-success' ou 'alert-danger'
    divMensagem.textContent = mensagem;

    // Adicionar a mensagem ao corpo da página
    document.body.appendChild(divMensagem);

    // Após 5 segundos, remover a mensagem
    setTimeout(() => {
        divMensagem.remove();
    }, 5000); // 5000ms = 5 segundos
}

// Exemplo de como você pode chamar a função para exibir uma mensagem de sucesso
exibirMensagem('Senha alterada com sucesso!', 'alert-success');

// Exemplo de como você pode chamar a função para exibir uma mensagem de erro
exibirMensagem('Erro ao alterar o CPF. Tente novamente.', 'alert-danger');
</script>
</body>
</html>
