<?php
include 'proteger.php';
include 'conexao.php';

$id_usuario = $_SESSION['id_usuario'];

$sql = "SELECT tempo_segundos, jogadas, modo, tamanho_tabuleiro, data_jogo FROM partidas WHERE id_usuario = ? ORDER BY data_jogo DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

$historico = [];
while ($row = $result->fetch_assoc()) {
    $historico[] = $row;
}

header('Content-Type: application/json');
echo json_encode($historico);

$stmt->close();
$conn->close();
?>