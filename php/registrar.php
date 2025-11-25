<?php
include 'proteger.php';
include 'conexao.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Método não permitido.']);
    exit;
}

$tempo = isset($_POST['tempo']) ? intval($_POST['tempo']) : 0;
$tentativas = isset($_POST['tentativas']) ? intval($_POST['tentativas']) : 0;
$pares = isset($_POST['pares']) ? intval($_POST['pares']) : 0;
$modo = isset($_POST['modo']) ? trim($_POST['modo']) : '';
$tabuleiro = isset($_POST['tabuleiro']) ? trim($_POST['tabuleiro']) : '';

$id_usuario = $_SESSION['id_usuario'];

if ($tempo <= 0 || $tentativas < 0 || empty($modo) || empty($tabuleiro)) {
    http_response_code(400);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Dados inválidos ou incompletos.']);
    exit;
}

$sql = "INSERT INTO partidas (id_usuario, tempo_segundos, jogadas, modo, tamanho_tabuleiro) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("iiiss", $id_usuario, $tempo, $tentativas, $modo, $tabuleiro);

    if ($stmt->execute()) {
        echo json_encode(['sucesso' => true, 'mensagem' => 'Partida registrada com sucesso.']);
    } else {
        http_response_code(500);
        error_log("Erro SQL: " . $stmt->error);
        echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao salvar no banco de dados.']);
    }
    $stmt->close();
} else {
    http_response_code(500);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro na preparação da query.']);
}

$conn->close();
?>
