<?php
include 'proteger.php';
include 'conexao.php';

$id_usuario = $_SESSION['id_usuario'];
$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if (empty($nome) || empty($email)) {
        $response['message'] = 'Nome e email são obrigatórios.';
    } else {
        $update_fields = [];
        $params = [];
        $types = '';

        $update_fields[] = 'nome = ?';
        $params[] = $nome;
        $types .= 's';

        $update_fields[] = 'email = ?';
        $params[] = $email;
        $types .= 's';

        if (!empty($senha)) {
            $hashed_senha = password_hash($senha, PASSWORD_DEFAULT);
            $update_fields[] = 'senha = ?';
            $params[] = $hashed_senha;
            $types .= 's';
        }

        $params[] = $id_usuario;
        $types .= 'i';

        $sql = "UPDATE usuarios SET " . implode(', ', $update_fields) . " WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param($types, ...$params);

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Perfil atualizado com sucesso.';
        } else {
            $response['message'] = 'Erro ao atualizar perfil.';
        }
        $stmt->close();
    }
} else {
    $response['message'] = 'Método não permitido.';
}

header('Content-Type: application/json');
echo json_encode($response);

$conn->close();
?>