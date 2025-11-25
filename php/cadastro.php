<?php

include 'conexao.php'; 

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Coletar e Sanitizar dados (do $_POST)
    // 2. Incluir o conexao.php
    // 3. Checar duplicidade (username/email)
    // 4. Gerar hash da senha
    // 5. Inserir no DB
    // 6. Retornar JSON (sucesso: true/false)
} else {
    // Retornar erro (Método não permitido)
}
?>
