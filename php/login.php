<?php
session_start();
header('Content-Type: application/json'); 

require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputJSON = file_get_contents('php://input');
    $input = json_decode($inputJSON, true);

    $usuario = $input['username'] ?? $_POST['username'] ?? '';
    $senha = $input['senha'] ?? $_POST['senha'] ?? '';

    if (empty($usuario) || empty($senha)) {
        echo json_encode(['success' => false, 'message' => 'Preencha todos os campos!']);
        exit;
    }

    $sql = "SELECT id, nome_completo, senha FROM usuarios WHERE username = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows === 1) {
            $dadosUsuario = $resultado->fetch_assoc();

            if (password_verify($senha, $dadosUsuario['senha'])) {
                session_regenerate_id(true);
                $_SESSION['id_usuario'] = $dadosUsuario['id'];
                $_SESSION['nome_completo'] = $dadosUsuario['nome_completo'] ?? 'Desconhecido';
                $_SESSION['usuario'] = $usuario;

                echo json_encode(['success' => true, 'message' => 'Login realizado com sucesso!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Usuário ou senha incorretos.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Usuário ou senha incorretos.']);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro interno no banco de dados.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método inválido.']);
}

$conn->close();
?>
