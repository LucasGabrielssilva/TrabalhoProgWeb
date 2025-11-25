<?php
include 'proteger.php';
include 'conexao.php';

$id_usuario = $_SESSION['id_usuario'];


$sql = "SELECT u.nome_completo, p.tempo_segundos, p.jogadas, p.modo, p.tamanho_tabuleiro, p.data_jogo
        FROM partidas p
        JOIN usuarios u ON p.id_usuario = u.id
        WHERE p.id_usuario = ?
        ORDER BY p.data_jogo DESC";
        
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

$historico = [];
while ($row = $result->fetch_assoc()) {
    $row['nome'] = $row['nome_completo'];
    $historico[] = $row;
}

header('Content-Type: application/json');
echo json_encode($historico);

$stmt->close();
$conn->close();
?>