<?php 
// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capturar os dados do formulário
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];

    // Validar se as senhas coincidem
    if ($password !== $confirmPassword) {
        echo "As senhas não coincidem.";
        exit;
    }

    // Validar se o email já existe no banco de dados
    $stmt = $pdo->prepare("SELECT * FROM cliente WHERE email = :email");
    $stmt->execute([$email]);

    if ($stmt->rowCount() > 0) {
        echo "Este email já está registrado.";
        exit;
    }

    // Hash da senha para segurança
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Inserir o novo usuário no banco de dados
    $stmt = $pdo->prepare("INSERT INTO cliente (name, email, senha) VALUES (?, ?, ?)");
    if ($stmt->execute([$name, $email, $hashedPassword])) {
        echo "Cadastro realizado com sucesso!";
        // Redirecionar para a página de login após o cadastro bem-sucedido
        header("Location: login.php");
        exit;
    } else {
        echo "Ocorreu um erro ao cadastrar, tente novamente.";
    }
}
?>