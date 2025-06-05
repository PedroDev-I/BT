<?php
session_start();
require '../config/config.php';

// Pega os dados do formulário de login
$codigo = filter_input(INPUT_POST, 'codigo', FILTER_SANITIZE_STRING); // Alterado para 'codigo'
$password = filter_input(INPUT_POST, 'password');

// Verifica se o código e a senha foram preenchidos
if ($codigo && $password) {
    // Verifica se o administrador com o código existe
    $sql = $pdo->prepare("SELECT * FROM funcionarios WHERE codigo = :codigo");
    $sql->bindValue(':codigo', $codigo);
    $sql->execute();

    if ($sql->rowCount() > 0) {
        // Administrador encontrado, verifica a senha
        $adm = $sql->fetch(PDO::FETCH_ASSOC);

        // Verifica se a senha informada corresponde ao hash armazenado
        if (password_verify($password, $adm['senha'])) {
            // Cria a sessão e redireciona para o painel do administrador
            session_regenerate_id(true);
            $_SESSION['id_funcionario'] = $adm['id_funcionario'];
            $_SESSION['nome'] = $adm['nome'];
            $_SESSION['codigo'] = $adm['codigo'];  // Armazena o código do administrador

            header("Location: ../pages/admdashboard.php"); // Redireciona para o painel de admin
            exit;
        } else {
            echo "Senha incorreta.";
        }
    } else {
        echo "Administrador não encontrado.";
    }
} else {
    echo "Preencha todos os campos!";
}
?>
