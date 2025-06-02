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
    <link rel="stylesheet" href="../assets/css/paginas/perfil.css">

    <!-- Links para o Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
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
                <li class="nav-item"><a class="nav-link active" href="#">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="#sobre">Sobre</a></li>
                <li class="nav-item"><a class="nav-link" href="#servicos">Serviços</a></li>
                <li class="nav-item"><a class="nav-link" href="#depoimentos">Depoimentos</a></li>

                <!-- Menu de Agendamentos só visível se o usuário estiver logado -->
                <?php if (isset($_SESSION['id_cliente'])): ?>
                    <li class="nav-item"><a class="nav-link" href="pages/agendamentos.php">Agendamentos</a></li>
                <?php endif; ?>
            </ul>

            <!-- Verifica se o usuário está logado -->
            <div class="login-box">
                <?php if (isset($_SESSION['id_cliente'])): ?>
                    <!-- Menu Dropdown para o nome do cliente -->
                    <div class="dropdown">
                        <button class="btn btn-link dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php echo htmlspecialchars($_SESSION['nome']); ?>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <li><a class="dropdown-item" href="pages/perfil.php">Perfil</a></li>
                            <li><a class="dropdown-item" href="../actions/logout.php">Sair</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a href="pages/login.php"><button type="submit">Entrar</button></a>
                    <a href="pages/registrar.php"><button type="submit">Registrar</button></a>
                <?php endif; ?>
            </div>
        </div>
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
                        <img id="foto" src="https://via.placeholder.com/150" alt="Foto de Perfil" width="150">
                    </div>
                    <div>
                        <label for="nome">Nome:</label>
                        <p id="nome"><?php echo htmlspecialchars($cliente['nome']); ?></p>
                    </div>
                    <div>
                        <label for="email">Email:</label>
                        <p id="email"><?php echo htmlspecialchars($cliente['email']); ?></p>
                    </div>
                    <div>
                        <label for="endereco">Endereço:</label>
                        <p id="endereco"><?php echo htmlspecialchars($cliente['endereco']); ?></p>
                    </div>

                    <!-- Botão para alterar senha -->
                    <div>
                        <button id="alterarSenhaBtn">Alterar Senha</button>
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
                                <th>#</th>
                                <th>Nome</th>
                                <th>Espécie</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($animais as $animal): ?>
                            <tr>
                                <td><?php echo $animal['id_animal']; ?></td>
                                <td><?php echo htmlspecialchars($animal['nome']); ?></td>
                                <td><?php echo htmlspecialchars($animal['tipo']); ?></td>
                                <td><button class="btn btn-info">Ver Detalhes</button></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#cadastrarPetModal">Cadastrar Pet</button>
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
                                <th>#</th>
                                <th>Serviço</th>
                                <th>Status</th>
                                <th>Ações</th>
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
                                <td><button class="btn btn-info">Ver Detalhes</button></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#cadastrarServicoModal">Cadastrar Serviço</button>
                </section>
            </div>
        </div>
    </div>
</main>

<!-- Scripts do Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
