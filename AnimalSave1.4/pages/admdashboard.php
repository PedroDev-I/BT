<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="../assets/css/paginas/adm.css" />
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
        <h2>Estoque</h2>
        <p>Gerencie os relatorios.</p>
      </section>
    </main>
  </div>

  <script>
    function mostrarAba(id) {
      const abas = document.querySelectorAll('.aba');
      abas.forEach(aba => aba.classList.remove('ativa'));
      document.getElementById(id).classList.add('ativa');
    }
  </script>
</body>
</html>
