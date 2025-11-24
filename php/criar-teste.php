<?php

require 'conexao.php'; 

// Dados do usuário
$nome = "Tester da Silva";
$usuario = "teste"; 
$senha_pura = "123456"; 
$email = "teste@email.com";
$cpf = "111.222.333-44";
$datanasc = "2000-01-01";

// CRIA O HASH DA SENHA 
$senha_hash = password_hash($senha_pura, PASSWORD_DEFAULT);

// Verifica se o usuário já existe para não dar erro
$check = $conn->query("SELECT id FROM usuarios WHERE usuario = '$usuario'");
if ($check->num_rows > 0) {
    die("O usuário 'teste' já existe! Tente fazer login.");
}

$sql = "INSERT INTO usuarios (nome, usuario, senha, email, cpf, datanasc) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("ssssss", $nome, $usuario, $senha_hash, $email, $cpf, $datanasc);
    if ($stmt->execute()) {
        echo "<h1>Sucesso! ✅</h1>";
        echo "Usuário criado.<br>";
        echo "Login: <b>teste</b><br>";
        echo "Senha: <b>123456</b><br>";
        echo "<br><a href='login.html'>Ir para o Login</a>";
    } else {
        echo "Erro ao criar: " . $stmt->error;
    }
} else {
    echo "Erro no banco: " . $conn->error;
}
?>