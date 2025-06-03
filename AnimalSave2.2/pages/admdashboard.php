<?php
require '../config/config.php';

// Consulta clientes
$sqlClientes = $pdo->query("SELECT nome, email, telefone, created FROM clientes");
$clientes = $sqlClientes->fetchAll(PDO::FETCH_ASSOC);

// Consulta serviços
$sqlServicos = $pdo->query("SELECT id_servico, nome, descricao, preco FROM servicos");
$servicos = $sqlServicos->fetchAll(PDO::FETCH_ASSOC);

// Consulta agendamentos
$sqlAgendamentos = $pdo->query("
  SELECT 
    a.id_agendamento,
    a.data_hora,
    a.status,
    a.valor,
    s.nome AS servico_nome
  FROM agendamentos a
  LEFT JOIN servicos s ON a.id_servico = s.id_servico
  ORDER BY a.data_hora DESC
");
$agendamentos = $sqlAgendamentos->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="../assets/css/paginas/adm.css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
</head>
<body>

<div class="dashboard-container">
  <aside class="sidebar">
    <h2>Admin</h2>
    <ul class="menu">
      <li onclick="mostrarAba('painel')">Painel</li>
      <li onclick="mostrarAba('clientes')">Clientes</li>
      <li onclick="mostrarAba('atendimentos')">Atendimentos</li>
      <li onclick="mostrarAba('servicos')">Serviços</li>
      <li onclick="mostrarAba('estoque')">Estoque</li>
      <li onclick="mostrarAba('relatorio')">Relatório</li>
    </ul>
  </aside>

  <main class="main-content">

    <!-- Painel -->
    <section id="painel" class="aba">
      <h2>Painel</h2>
      <p>Conteúdo do painel aqui.</p>
    </section>

    <!-- Clientes -->
    <section id="clientes" class="aba">
      <h2>Clientes</h2>
      <p>Gerencie os dados dos clientes aqui.</p>

      <table id="tabela-clientes" class="display" style="width:100%">
        <thead>
          <tr>
            <th>Nome</th>
            <th>Email</th>
            <th>Telefone</th>
            <th>Data de Cadastro</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($clientes as $cliente): ?>
            <tr>
              <td><?= htmlspecialchars($cliente['nome']) ?></td>
              <td><?= htmlspecialchars($cliente['email']) ?></td>
              <td><?= htmlspecialchars($cliente['telefone']) ?></td>
              <td><?= date('d/m/Y', strtotime($cliente['created'])) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </section>

    <!-- Atendimentos -->
    <section id="atendimentos" class="aba ativa">
      <h2>Atendimentos</h2>
      <p>Registros e agendamentos de atendimentos.</p>

      <table id="tabela-agendamentos" class="display" style="width:100%">
        <thead>
          <tr>
            <th>ID</th>
            <th>Data/Hora</th>
            <th>Serviço</th>
            <th>Status</th>
            <th>Valor</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($agendamentos as $ag): ?>
            <tr>
              <td><?= htmlspecialchars($ag['id_agendamento']) ?></td>
              <td><?= date('d/m/Y H:i', strtotime($ag['data_hora'])) ?></td>
              <td><?= htmlspecialchars($ag['servico_nome']) ?></td>
              <td><?= ucfirst($ag['status']) ?></td>
              <td>R$ <?= number_format($ag['valor'], 2, ',', '.') ?></td>
              <td>
                <a href="nota.php?id_agendamento=<?= $ag['id_agendamento'] ?>" class="botao-nota" target="_blank">Ver Nota</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </section>

    <!-- Serviços -->
    <section id="servicos" class="aba">
      <h2>Serviços</h2>
      <p>Lista e controle dos serviços oferecidos.</p>

      <table id="tabela-servicos" class="display" style="width:100%">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Descrição</th>
            <th>Preço</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($servicos as $servico): ?>
            <tr>
              <td><?= htmlspecialchars($servico['id_servico']) ?></td>
              <td><?= htmlspecialchars($servico['nome']) ?></td>
              <td><?= htmlspecialchars($servico['descricao'] ?? '-') ?></td>
              <td>R$ <?= number_format($servico['preco'], 2, ',', '.') ?></td>
              <td>
                <a href="nota.php?id_servico=<?= $servico['id_servico'] ?>" class="botao-nota" target="_blank">Ver Nota</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </section>

    <!-- Estoque -->
    <section id="estoque" class="aba">
      <h2>Estoque</h2>
      <p>Gerencie os itens em estoque.</p>
    </section>

    <!-- Relatório -->
    <section id="relatorio" class="aba">
      <h2>Relatório</h2>
      <p>Gerencie os relatórios.</p>
    </section>

  </main>
</div>

<!-- jQuery + DataTables -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
  function mostrarAba(id) {
    document.querySelectorAll('.aba').forEach(aba => aba.classList.remove('ativa'));
    document.getElementById(id).classList.add('ativa');
  }

  $(document).ready(function() {
    $('#tabela-clientes').DataTable({ language: { url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json' } });
    $('#tabela-servicos').DataTable({ language: { url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json' } });
    $('#tabela-agendamentos').DataTable({ language: { url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json' } });
  });
</script>

<style>
  .aba { display: none; }
  .aba.ativa { display: block; }
  .botao-nota {
    padding: 5px 10px;
    background-color: #28a745;
    color: white;
    text-decoration: none;
    border-radius: 4px;
  }
  .botao-nota:hover {
    background-color: #218838;
  }
</style>
</body>
</html>
