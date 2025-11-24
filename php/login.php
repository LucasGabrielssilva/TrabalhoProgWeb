<?php
// login.php
session_start();
header('Content-Type: application/json'); 


require_once 'conexao.php';

// Verifica se os dados foram enviados via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Pega os dados enviados (pode vir via JSON ou Form Data)
    
    $inputJSON = file_get_contents('php://input');
    $input = json_decode($inputJSON, true);
    
    // Se vier via form data padrão ou JSON decodificado
    $usuario = $input['usuario'] ?? $_POST['usuario'] ?? '';
    $senha = $input['senha'] ?? $_POST['senha'] ?? '';

    // Validação básica
    if (empty($usuario) || empty($senha)) {
        echo json_encode(['success' => false, 'message' => 'Preencha todos os campos!']);
        exit;
    }


    $sql = "SELECT id, nome, senha FROM usuarios WHERE usuario = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows === 1) {
            $dadosUsuario = $resultado->fetch_assoc();

            // VERIFICAÇÃO DA SENHA (password_verify)
            if (password_verify($senha, $dadosUsuario['senha'])) {
                
                // Login com Sucesso: Segurança de Sessão
                session_regenerate_id(true); // Previne fixação de sessão (Requisito Pessoa 3)
                
                $_SESSION['id_usuario'] = $dadosUsuario['id'];
                $_SESSION['nome'] = $dadosUsuario['nome'];
                $_SESSION['usuario'] = $usuario;

                echo json_encode(['success' => true, 'message' => 'Login realizado com sucesso!']);
            } else {
                // Senha incorreta
                echo json_encode(['success' => false, 'message' => 'Usuário ou senha incorretos.']);
            }
        } else {
            // Usuário não encontrado
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