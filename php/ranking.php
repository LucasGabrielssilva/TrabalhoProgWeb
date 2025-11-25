<?php
ini_set('display_errors', 0); // não mostra erros na tela
error_reporting(0);
include 'conexao.php';


$sql = "SELECT 
            u.username, 
            COUNT(*) as vitorias,
            SUM(p.jogadas) as total_jogadas,
            AVG(p.tempo_segundos) as tempo_medio
        FROM usuarios u
        JOIN partidas p ON u.id = p.id_usuario
        GROUP BY u.id, u.username
        ORDER BY vitorias DESC, tempo_medio ASC
        LIMIT 10";

$result = $conn->query($sql);

$ranking = [];
$posicao = 1;
while ($row = $result->fetch_assoc()) {
    $row['posicao'] = $posicao++;
    $ranking[] = $row;
}

header('Content-Type: application/json');
echo json_encode($ranking);

$conn->close();
?>