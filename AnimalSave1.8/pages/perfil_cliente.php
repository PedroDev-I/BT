<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['id_cliente'])) {
    // Se não estiver logado, redireciona para a página de login com a mensagem de erro
    header('Location: ../pages/login.php?error=1');
    exit(); // Interrompe a execução do código
}
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
                            <?php echo $_SESSION['nome']; ?>
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
                        <p id="nome">João da Silva</p>
                    </div>
                    <div>
                        <label for="email">Email:</label>
                        <p id="email">joao@example.com</p>
                    </div>
                    <div>
                        <label for="endereco">Endereço:</label>
                        <p id="endereco">Rua Exemplo, 123 - Bairro Feliz, Cidade - Estado</p>
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
                            <tr>
                                <td>1</td>
                                <td>Fido</td>
                                <td>Cachorro</td>
                                <td><button class="btn btn-info">Ver Detalhes</button></td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Whiskers</td>
                                <td>Gato</td>
                                <td><button class="btn btn-info">Ver Detalhes</button></td>
                            </tr>
                        </tbody>
                    </table>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#cadastrarPetModal">Cadastrar Pet</button>
                </section>
            </div>
        </div>

        <!-- Modal de Cadastro de Pet -->
        <div class="modal fade" id="cadastrarPetModal" tabindex="-1" aria-labelledby="cadastrarPetModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="cadastrarPetModalLabel">Cadastrar Novo Pet</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="mb-3">
                                <label for="petNome" class="form-label">Nome do Pet</label>
                                <input type="text" class="form-control" id="petNome" required>
                            </div>
                            <div class="mb-3">
                                <label for="petEspecie" class="form-label">Espécie</label>
                                <input type="text" class="form-control" id="petEspecie" required>
                            </div>
                            <div class="mb-3">
                                <label for="petRaca" class="form-label">Raça</label>
                                <input type="text" class="form-control" id="petRaca" required>
                            </div>
                            <div class="mb-3">
                                <label for="petIdade" class="form-label">Idade</label>
                                <input type="number" class="form-control" id="petIdade" required>
                            </div>
                            <button type="submit" class="btn btn-success">Cadastrar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

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
    </div>
    <div class="roda-pe-footer">
        <p>&copy; 2025 AnimalSave. Todos os direitos reservados.</p>
    </div>
</footer>

<!-- Scripts do Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
