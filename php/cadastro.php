<?php
include 'conexao.php'; 

header('Content-Type: application/json');

// 1. Verifica se o método de requisição é POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Método não permitido.']);
    exit;
}

// Verifica se a conexão falhou (conforme a lógica do seu conexao.php)
if ($conn->connect_error) {
    // A Pessoa 1 deve garantir que este tratamento de erro seja robusto.
    error_log("Falha na conexão DB: " . $conn->connect_error);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao conectar ao banco de dados.']);
    exit;
}

// 2. Coletar, Sanitizar Dados e mapear nomes do HTML (name="")
$nome_completo = trim($_POST['nome'] ?? '');
$data_nascimento = $_POST['datanasc'] ?? '';
$cpf = trim($_POST['cpf'] ?? '');
$telefone = trim($_POST['telefone'] ?? '');
$email = trim(strtolower($_POST['email'] ?? ''));
$username = trim(strtolower($_POST['usuario'] ?? ''));
$senha_pura = $_POST['senha'] ?? '';
$confirmasenha = $_POST['confirmasenha'] ?? '';


// --- INÍCIO DAS VALIDAÇÕES ---

// Validação de Senhas
if ($senha_pura !== $confirmasenha) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'As senhas digitadas não coincidem.']);
    exit;
}

// Validação de Campos Obrigatórios
if (empty($username) || empty($email) || empty($senha_pura) || empty($nome_completo)) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Preencha todos os campos obrigatórios.']);
    exit;
}

// Validação de formato de E-mail
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Formato de email inválido.']);
    exit;
}

// 3. Checar duplicidade (username/email) usando Prepared Statement (mysqli)
$query_duplicidade = "SELECT COUNT(*) FROM usuarios WHERE username = ? OR email = ?";
$stmt_check = $conn->prepare($query_duplicidade);

// 'ss' significa que estamos passando duas strings ($username, $email)
$stmt_check->bind_param("ss", $username, $email); 
$stmt_check->execute();
$stmt_check->bind_result($count); // Liga o resultado da contagem à variável $count
$stmt_check->fetch(); // Busca o resultado

if ($count > 0) {
    $stmt_check->close();
    echo json_encode(['sucesso' => false, 'mensagem' => 'Usuário ou Email já cadastrados.']);
    exit;
}
$stmt_check->close(); // Fecha o statement de checagem


// --- FIM DAS VALIDAÇÕES ---

// 4. Gerar hash da senha
$senha_hash = password_hash($senha_pura, PASSWORD_DEFAULT);


// 5. Inserir no DB usando Prepared Statement (mysqli)
$sql_insert = "INSERT INTO usuarios (nome_completo, data_nascimento, cpf, telefone, email, username, senha) 
               VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt_insert = $conn->prepare($sql_insert);

// 'sssssss' significa que todos os 7 parâmetros são strings
$stmt_insert->bind_param("sssssss", 
    $nome_completo, 
    $data_nascimento, 
    $cpf, 
    $telefone, 
    $email, 
    $username, 
    $senha_hash
);

if ($stmt_insert->execute()) {
    // 6. Retornar JSON (sucesso: true)
    $stmt_insert->close();
    $conn->close();
    echo json_encode(['sucesso' => true, 'mensagem' => 'Cadastro realizado com sucesso!']);
} else {
    // Retornar erro de inserção
    error_log("Erro ao inserir usuário: " . $stmt_insert->error);
    $stmt_insert->close();
    $conn->close();
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao finalizar o cadastro.']);
}

?>
