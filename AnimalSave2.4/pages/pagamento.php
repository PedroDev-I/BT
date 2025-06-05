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

// Buscar dados do agendamento
$stmt = $pdo->prepare("
    SELECT Ag.*, An.nome AS nome_animal, S.nome AS nome_servico
    FROM Agendamentos Ag
    JOIN Animais An ON Ag.id_animal = An.id_animal
    JOIN Servicos S ON Ag.id_servico = S.id_servico
    WHERE Ag.id_agendamento = ?
");
$stmt->execute([$id_agendamento]);
$agendamento = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$agendamento) {
    echo "Agendamento não encontrado.";
    exit();
}

// Simulação de pagamento
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $forma_pagamento = $_POST['forma_pagamento'] ?? '';

    // Validação simples backend (pode expandir)
    if ($forma_pagamento === 'Cartão de Crédito') {
        $numero_cartao = preg_replace('/\D/', '', $_POST['numero_cartao'] ?? '');
        $validade = $_POST['validade'] ?? '';
        $cvv = $_POST['cvv'] ?? '';

        if (strlen($numero_cartao) < 13 || strlen($numero_cartao) > 19) {
            echo "<p style='color:red;'>Número do cartão inválido.</p>";
            exit();
        }
        if (!preg_match('/^(0[1-9]|1[0-2])\/\d{2}$/', $validade)) {
            echo "<p style='color:red;'>Validade inválida. Use MM/AA.</p>";
            exit();
        }
        if (!preg_match('/^\d{3,4}$/', $cvv)) {
            echo "<p style='color:red;'>CVV inválido.</p>";
            exit();
        }
        // Aqui você pode adicionar validação Luhn se quiser (não implementada)
    } elseif ($forma_pagamento === 'PIX') {
        $chave_pix = $_POST['chave_pix'] ?? '';
        // Sem validação complexa, só simulação
    } elseif ($forma_pagamento === 'Dinheiro') {
        // Sem validação extra
    } else {
        echo "<p style='color:red;'>Forma de pagamento inválida.</p>";
        exit();
    }

    $valor_pago = $agendamento['valor'];
    $data_pagamento = date('Y-m-d H:i:s');

    $stmt_pg = $pdo->prepare("
        INSERT INTO Pagamentos (id_agendamento, forma_pagamento, valor_pago, data_pagamento) 
        VALUES (?, ?, ?, ?)
    ");
    $stmt_pg->execute([$id_agendamento, $forma_pagamento, $valor_pago, $data_pagamento]);

    $stmt_up = $pdo->prepare("UPDATE Agendamentos SET status = 'pago' WHERE id_agendamento = ?");
    $stmt_up->execute([$id_agendamento]);

    echo '
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f8f9fa;
        text-align: center;
        padding: 40px;
    }
    @keyframes zoomIn {
        from {
            opacity: 0;
            transform: scale(0.5);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }
    .success-wrapper {
        background: #fff;
        border-radius: 12px;
        max-width: 500px;
        margin: 0 auto;
        padding: 40px 30px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        animation: zoomIn 0.6s ease-out;
    }
    .success-icon {
        width: 100px;
        height: 100px;
        background: #28a745;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
    }
    .success-icon svg {
        width: 50px;
        height: 50px;
        color: white;
    }
    .success-message {
        font-size: 22px;
        font-weight: bold;
        color: #333;
        margin-bottom: 10px;
    }
    .success-subtext {
        font-size: 16px;
        color: #666;
        margin-bottom: 30px;
    }
    .btn-nota {
        background: #007bff;
        color: white;
        padding: 10px 20px;
        font-size: 16px;
        border: none;
        border-radius: 6px;
        text-decoration: none;
        transition: background 0.3s;
    }
    .btn-nota:hover {
        background: #0056b3;
    }
</style>

<div class="success-wrapper">
    <div class="success-icon">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
    </div>
    <div class="success-message">Pagamento realizado com sucesso!</div>
    <div class="success-subtext">Obrigado por confiar na AnimalSave 🐾</div>
    <a href="nota.php?id_agendamento=' . $id_agendamento . '" class="btn-nota">Ver Nota Fiscal</a>
</div>';
exit();

}

$pixKey = 'animal.save@pix.com.br';

// Gerar QR code PIX dinâmico (formato padrão PIX)
function gerarPixPayload($chave, $descricao = 'Pagamento AnimalSave', $valor = null) {
    $payload = "00020126"; // Payload Format Indicator + Merchant Account Info
    $payload .= "0014br.gov.bcb.pix"; // GUI PIX
    $payload .= sprintf("%02d%s", strlen($chave), $chave);
    if ($descricao) {
        $payload .= sprintf("%02d%s", strlen($descricao), $descricao);
    }
    if ($valor) {
        $payload .= "54" . sprintf("%02d%s", strlen(number_format($valor, 2, '.', '')), number_format($valor, 2, '.', ''));
    }
    $payload .= "5802BR"; // Country code
    $payload .= "5303986"; // Currency (986 = BRL)
    $payload .= "6304"; // CRC16 placeholder (to be calculated)
    
    // Calcular CRC16
    $crc = strtoupper(dechex(crc16($payload)));
    while (strlen($crc) < 4) {
        $crc = "0" . $crc;
    }
    return substr($payload, 0, -4) . $crc;
}

// Função CRC16 (padrão para PIX)
function crc16($payload) {
    $polinomio = 0x1021;
    $resultado = 0xFFFF;
    $bytes = str_split($payload);
    foreach ($bytes as $b) {
        $resultado ^= ord($b) << 8;
        for ($i = 0; $i < 8; $i++) {
            if (($resultado & 0x8000) !== 0) {
                $resultado = ($resultado << 1) ^ $polinomio;
            } else {
                $resultado <<= 1;
            }
            $resultado &= 0xFFFF;
        }
    }
    return $resultado;
}

$pixPayload = gerarPixPayload($pixKey, "Pagamento AnimalSave", $agendamento['valor']);
$pixQRCodeURL = "https://api.qrserver.com/v1/create-qr-code/?data=" . urlencode($pixPayload) . "&size=150x150";

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>Pagamento - AnimalSave</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        #cartao-info, #pix-info {
            display: none;
            margin-top: 15px;
        }
        .qrcode {
            width: 150px;
            height: 150px;
        }
        .input-error {
            border-color: #dc3545 !important;
        }
        .error-message {
            color: #dc3545;
            font-size: 0.875em;
        }
    </style>
</head>
<body class="container py-5">
    <h2 class="mb-4">Pagamento do Agendamento</h2>

    <div class="mb-3">
        <strong>Animal:</strong> <?= htmlspecialchars($agendamento['nome_animal']) ?><br />
        <strong>Serviço:</strong> <?= htmlspecialchars($agendamento['nome_servico']) ?><br />
        <strong>Data/Hora:</strong> <?= date('d/m/Y H:i', strtotime($agendamento['data_hora'])) ?><br />
        <strong>Valor:</strong> R$ <?= number_format($agendamento['valor'], 2, ',', '.') ?>
    </div>

    <form method="POST" id="form-pagamento" novalidate>
        <div class="mb-3">
            <label for="forma_pagamento" class="form-label">Forma de Pagamento</label>
            <select name="forma_pagamento" id="forma_pagamento" class="form-select" required>
                <option value="" selected>Selecione</option>
                <option value="Dinheiro">Dinheiro</option>
                <option value="Cartão de Crédito">Cartão de Crédito</option>
                <option value="PIX">PIX</option>
            </select>
            <div class="error-message" id="forma_pagamento_error"></div>
        </div>

        <div id="cartao-info" aria-live="polite">
            <div class="mb-3">
                <label for="numero_cartao" class="form-label">Número do Cartão</label>
                <input type="text" class="form-control" id="numero_cartao" name="numero_cartao" placeholder="1234 5678 9012 3456" maxlength="19" inputmode="numeric" autocomplete="cc-number" />
                <div class="error-message" id="numero_cartao_error"></div>
            </div>
            <div class="mb-3 row">
                <div class="col-6">
                    <label for="validade" class="form-label">Validade (MM/AA)</label>
                    <input type="text" class="form-control" id="validade" name="validade" placeholder="MM/AA" maxlength="5" autocomplete="cc-exp" />
                    <div class="error-message" id="validade_error"></div>
                </div>
                <div class="col-6">
                    <label for="cvv" class="form-label">CVV</label>
                    <input type="password" class="form-control" id="cvv" name="cvv" maxlength="4" placeholder="123" inputmode="numeric" autocomplete="cc-csc" />
                    <div class="error-message" id="cvv_error"></div>
                </div>
            </div>
        </div>

        <div id="pix-info" aria-live="polite">
            <p><strong>Chave PIX para pagamento:</strong> <span id="pix-key"><?= htmlspecialchars($pixKey) ?></span>
            <button type="button" class="btn btn-sm btn-outline-secondary ms-2" id="copy-pix">Copiar chave</button></p>
            <p>Escaneie o QR Code abaixo:</p>
            <img src="<?= $pixQRCodeURL ?>" alt="QR Code PIX" class="qrcode" />
        </div>

        <button type="submit" class="btn btn-primary mt-3">Confirmar Pagamento</button>
    </form>

    <script>
        // Mostrar/ocultar campos
        const formaPagamento = document.getElementById('forma_pagamento');
        const cartaoInfo = document.getElementById('cartao-info');
        const pixInfo = document.getElementById('pix-info');

        function resetErrors() {
            document.querySelectorAll('.error-message').forEach(el => el.textContent = '');
            document.querySelectorAll('.input-error').forEach(el => el.classList.remove('input-error'));
        }

        function validaNumeroCartao(num) {
            // Limpa não numéricos
            num = num.replace(/\D/g, '');

            if (num.length < 13 || num.length > 19) return false;

            // Validação Luhn
            let sum = 0;
            let alt = false;
            for (let i = num.length - 1; i >= 0; i--) {
                let n = parseInt(num.charAt(i), 10);
                if (alt) {
                    n *= 2;
                    if (n > 9) n -= 9;
                }
                sum += n;
                alt = !alt;
            }
            return sum % 10 === 0;
        }

        function validaValidade(val) {
            if (!/^(0[1-9]|1[0-2])\/\d{2}$/.test(val)) return false;
            const [mes, ano] = val.split('/');
            const anoNum = 2000 + parseInt(ano, 10);
            const mesNum = parseInt(mes, 10);
            const hoje = new Date();
            const expira = new Date(anoNum, mesNum - 1, 1);
            expira.setMonth(expira.getMonth() + 1); // próximo mês
            return expira > hoje;
        }

        function validaCVV(cvv) {
            return /^\d{3,4}$/.test(cvv);
        }

        formaPagamento.addEventListener('change', () => {
            resetErrors();
            if (formaPagamento.value === 'Cartão de Crédito') {
                cartaoInfo.style.display = 'block';
                pixInfo.style.display = 'none';
                document.getElementById('numero_cartao').required = true;
                document.getElementById('validade').required = true;
                document.getElementById('cvv').required = true;
            } else if (formaPagamento.value === 'PIX') {
                cartaoInfo.style.display = 'none';
                pixInfo.style.display = 'block';
                document.getElementById('numero_cartao').required = false;
                document.getElementById('validade').required = false;
                document.getElementById('cvv').required = false;
            } else {
                cartaoInfo.style.display = 'none';
                pixInfo.style.display = 'none';
                document.getElementById('numero_cartao').required = false;
                document.getElementById('validade').required = false;
                document.getElementById('cvv').required = false;
            }
        });

        // Máscaras simples para cartão e validade
        const numeroCartaoInput = document.getElementById('numero_cartao');
        numeroCartaoInput.addEventListener('input', e => {
            let val = e.target.value.replace(/\D/g, '').substring(0, 16);
            let formatted = val.replace(/(.{4})/g, '$1 ').trim();
            e.target.value = formatted;
        });

        const validadeInput = document.getElementById('validade');
        validadeInput.addEventListener('input', e => {
            let val = e.target.value.replace(/\D/g, '').substring(0, 4);
            if (val.length >= 3) {
                val = val.substring(0,2) + '/' + val.substring(2);
            }
            e.target.value = val;
        });

        // Copiar chave PIX
        document.getElementById('copy-pix').addEventListener('click', () => {
            const chave = document.getElementById('pix-key').textContent;
            navigator.clipboard.writeText(chave).then(() => {
                alert('Chave PIX copiada!');
            }).catch(() => {
                alert('Erro ao copiar chave PIX.');
            });
        });

        // Validação no submit
        document.getElementById('form-pagamento').addEventListener('submit', (e) => {
            resetErrors();
            let valid = true;

            if (!formaPagamento.value) {
                valid = false;
                document.getElementById('forma_pagamento_error').textContent = 'Selecione a forma de pagamento.';
                formaPagamento.classList.add('input-error');
            }

            if (formaPagamento.value === 'Cartão de Crédito') {
                const numCartao = numeroCartaoInput.value.trim();
                const validade = validadeInput.value.trim();
                const cvv = document.getElementById('cvv').value.trim();

                if (!validaNumeroCartao(numCartao)) {
                    valid = false;
                    document.getElementById('numero_cartao_error').textContent = 'Número do cartão inválido.';
                    numeroCartaoInput.classList.add('input-error');
                }
                if (!validaValidade(validade)) {
                    valid = false;
                    document.getElementById('validade_error').textContent = 'Validade inválida ou expirada.';
                    validadeInput.classList.add('input-error');
                }
                if (!validaCVV(cvv)) {
                    valid = false;
                    document.getElementById('cvv_error').textContent = 'CVV inválido.';
                    document.getElementById('cvv').classList.add('input-error');
                }
            }

            if (!valid) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>
