<?php 
require "../config/config.php";

// Pega os dados do formulário
$nome = filter_input(INPUT_POST, "name", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
$senha = filter_input(INPUT_POST, "password");

// Verifica se todos os campos obrigatórios foram preenchidos
if ($nome && $email && $senha) {
    // Verifica se o e-mail já está cadastrado
    $sql = $pdo->prepare("SELECT * FROM clientes WHERE email = :email");
    $sql->bindValue(':email', $email);
    $sql->execute();

    if ($sql->rowCount() === 0) {
        // Insere os dados no banco de dados
        $sql = $pdo->prepare("INSERT INTO clientes (nome, email, senha) VALUES (:nome, :email, :senha)");
        $sql->bindValue(':nome', $nome);
        $sql->bindValue(':email', $email);
        $sql->bindValue(':senha', password_hash($senha, PASSWORD_DEFAULT)); 
        $sql->execute();

        // Redireciona para a página de login após o cadastro
        header("Location: ../pages/login.php?cadastro=sucesso"); 
exit;
    } else {
        echo "E-mail já cadastrado.";
    }
} else {
    echo "Preencha todos os campos corretamente.";
}
?>
