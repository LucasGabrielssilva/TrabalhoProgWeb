<?php
include 'conexao.php'; 
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Método não permitido.']);
    exit;
}

if ($conn->connect_error) {
    error_log("Falha na conexão DB: " . $conn->connect_error);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao conectar ao banco de dados.']);
    exit;
}

$nome_completo = trim($_POST['nome'] ?? '');
$data_nascimento = $_POST['datanasc'] ?? '';
$cpf = trim($_POST['cpf'] ?? '');
$telefone = trim($_POST['telefone'] ?? '');
$email = trim(strtolower($_POST['email'] ?? ''));
$username = trim(strtolower($_POST['usuario'] ?? ''));
$senha_pura = $_POST['senha'] ?? '';
$confirmasenha = $_POST['confirmasenha'] ?? '';

if ($senha_pura !== $confirmasenha) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'As senhas digitadas não coincidem.']);
    exit;
}

if (empty($username) || empty($email) || empty($senha_pura) || empty($nome_completo)) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Preencha todos os campos obrigatórios.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Formato de email inválido.']);
    exit;
}

$query_duplicidade = "SELECT COUNT(*) FROM usuarios WHERE username = ? OR email = ?";
$stmt_check = $conn->prepare($query_duplicidade);
$stmt_check->bind_param("ss", $username, $email);
$stmt_check->execute();
$stmt_check->bind_result($count);
$stmt_check->fetch();
if ($count > 0) {
    $stmt_check->close();
    echo json_encode(['sucesso' => false, 'mensagem' => 'Usuário ou Email já cadastrados.']);
    exit;
}
$stmt_check->close();

$senha_hash = password_hash($senha_pura, PASSWORD_DEFAULT);

$sql_insert = "INSERT INTO usuarios (nome_completo, data_nascimento, cpf, telefone, email, username, senha) 
               VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt_insert = $conn->prepare($sql_insert);
$stmt_insert->bind_param("sssssss", $nome_completo, $data_nascimento, $cpf, $telefone, $email, $username, $senha_hash);

if ($stmt_insert->execute()) {
    $stmt_insert->close();
    $conn->close();
    echo json_encode(['sucesso' => true, 'mensagem' => 'Cadastro realizado com sucesso!']);
} else {
    error_log("Erro ao inserir usuário: " . $stmt_insert->error);
    $stmt_insert->close();
    $conn->close();
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao finalizar o cadastro.']);
}
?>
