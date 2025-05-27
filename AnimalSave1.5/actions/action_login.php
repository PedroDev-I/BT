<?php
session_start();
require '../config/config.php';

// Pega os dados do formulário
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$senha = filter_input(INPUT_POST, 'senha');

// Verifica se o email e senha foram preenchidos
if ($email && $senha) {
    // Verifica se o usuário existe
    $sql = $pdo->prepare("SELECT * FROM clientes WHERE email = :email");
    $sql->bindValue(':email', $email);
    $sql->execute();

    if ($sql->rowCount() > 0) {
        // Usuário encontrado, verifica a senha
        $usuario = $sql->fetch(PDO::FETCH_ASSOC);

        if (password_verify($senha, $usuario['senha'])) {
            // Cria a sessão e redireciona
            session_regenerate_id(true);
            $_SESSION['id_cliente'] = $usuario['id_cliente'];
            $_SESSION['nome'] = $usuario['nome'];

            header("Location: ../index.html");
            exit;
        } else {
            echo "Senha incorreta.";
        }
    } else {
        echo "Usuário não encontrado.";
    }
} else {
    echo "Preencha todos os campos!";
}
?>
