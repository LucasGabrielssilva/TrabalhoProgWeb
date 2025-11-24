<?php
include 'conexao.php';

// Ranking by least time, then least attempts (best score per user)
$sql = "SELECT u.nome, MIN(p.tempo_segundos) as melhor_tempo, MIN(p.jogadas) as melhor_jogadas
        FROM usuarios u
        JOIN partidas p ON u.id = p.id_usuario
        GROUP BY u.id, u.nome
        ORDER BY melhor_tempo ASC, melhor_jogadas ASC
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