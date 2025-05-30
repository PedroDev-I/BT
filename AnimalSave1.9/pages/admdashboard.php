<?php
require '../config/config.php';

// Faz a consulta uma vez e guarda os resultados na variável $clientes
$sql = $pdo->query("SELECT nome, email, telefone, created FROM clientes");
$clientes = [];
if($sql->rowCount() > 0){
    $clientes = $sql->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="../assets/css/paginas/adm.css" />

  <!-- DataTables CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
</head>
<body>

  <div class="dashboard-container">
    <aside class="sidebar">
      <h2>Admin</h2>
      <ul class="menu">
        <li onclick="mostrarAba('clientes')">Clientes</li>
        <li onclick="mostrarAba('atendimentos')">Atendimentos</li>
        <li onclick="mostrarAba('servicos')">Serviços</li>
        <li onclick="mostrarAba('estoque')">Estoque</li>
        <li onclick="mostrarAba('relatorio')">Relatório</li>
      </ul>
    </aside>

    <main class="main-content">
      <section id="clientes" class="aba ativa">
        <h2>Clientes</h2>
        <p>Gerencie os dados dos clientes aqui.</p>

        <!-- Tabela Clientes -->
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

      <section id="atendimentos" class="aba">
        <h2>Atendimentos</h2>
        <p>Registros e agendamentos de atendimentos.</p>
      </section>

      <section id="servicos" class="aba">
        <h2>Serviços</h2>
        <p>Lista e controle dos serviços oferecidos.</p>
      </section>

      <section id="estoque" class="aba">
        <h2>Estoque</h2>
        <p>Gerencie os itens em estoque.</p>
      </section>

      <section id="relatorio" class="aba">
        <h2>Relatório</h2>
        <p>Gerencie os relatórios.</p>
      </section>
    </main>
  </div>

  <!-- jQuery (necessário para DataTables) -->
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

  <!-- DataTables JS -->
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

  <script>
    function mostrarAba(id) {
      const abas = document.querySelectorAll('.aba');
      abas.forEach(aba => aba.classList.remove('ativa'));
      document.getElementById(id).classList.add('ativa');
    }

    // Inicializa o DataTable quando a página carregar
    $(document).ready(function() {
      $('#tabela-clientes').DataTable({
        language: {
          url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json'
        }
      });
    });
  </script>
</body>
</html>
