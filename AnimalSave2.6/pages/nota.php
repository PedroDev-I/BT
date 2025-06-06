<?php
include('../config/config.php');
session_start();

if (!isset($_SESSION['id_cliente'])) {
    header('Location: login.php');
    exit();
}

if (!isset($_GET['id_agendamento'])) {
    echo "Agendamento inválido.";
    exit();
}

$id_agendamento = $_GET['id_agendamento'];

// Consulta completa
$stmt = $pdo->prepare("
    SELECT 
        Ag.*, 
        An.nome AS nome_animal,
        Cl.nome AS nome_cliente,
        Cl.cpf,
        S.nome AS nome_servico,
        Pg.forma_pagamento,
        Pg.valor_pago,
        Pg.data_pagamento
    FROM Agendamentos Ag
    JOIN Animais An ON Ag.id_animal = An.id_animal
    JOIN Clientes Cl ON An.id_cliente = Cl.id_cliente
    JOIN Servicos S ON Ag.id_servico = S.id_servico
    LEFT JOIN Pagamentos Pg ON Pg.id_agendamento = Ag.id_agendamento
    WHERE Ag.id_agendamento = ?
");

$stmt->execute([$id_agendamento]);
$nota = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$nota) {
    echo "Dados da nota não encontrados.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Nota do Agendamento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/nota/nota.css">
</head>
<body class="container py-5">
    <h2 class="mb-4">Nota de Serviço</h2>

    <div class="mb-3">
        <strong>Cliente:</strong> <?= htmlspecialchars($nota['nome_cliente']) ?><br>
        <strong>CPF:</strong> <?= htmlspecialchars($nota['cpf']) ?>
    </div>

    <div class="mb-3">
        <strong>Animal:</strong> <?= htmlspecialchars($nota['nome_animal']) ?><br>
        <strong>Serviço:</strong> <?= htmlspecialchars($nota['nome_servico']) ?><br>
        <strong>Data:</strong> <?= date('d/m/Y H:i', strtotime($nota['data_hora'])) ?>
    </div>

    <div class="mb-3">
        <strong>Valor:</strong> R$ <?= number_format($nota['valor'], 2, ',', '.') ?><br>
        <strong>Forma de Pagamento:</strong> <?= htmlspecialchars($nota['forma_pagamento']) ?><br>
        <strong>Data do Pagamento:</strong> <?= $nota['data_pagamento'] ? date('d/m/Y H:i', strtotime($nota['data_pagamento'])) : 'Pendente' ?>
    </div>

    <div class="d-flex gap-2 mt-4 no-print">
        <a href="../index.php" class="btn btn-secondary">Voltar ao Início</a>
        <button class="btn btn-success" onclick="window.print()">Imprimir Nota</button>
    </div>
</body>
</html>
