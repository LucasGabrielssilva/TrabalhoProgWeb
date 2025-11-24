

async function login(usuario, senha) {
    // Validação simples no frontend
    if (!usuario || !senha) {
        alert("Por favor, preencha usuário e senha.");
        return;
    }

    try {
        // Faz a requisição para o backend (login.php)
        const response = await fetch('/TrabalhoProgWeb/php/login.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ usuario: usuario, senha: senha })
        });

        const data = await response.json();

        if (data.success) {
            // Sucesso: Redireciona para o jogo
            window.location.href = "jogoMemoria.html"; 
        } else {
            // Erro: Mostra alerta com a mensagem vinda do PHP
            alert(data.message);
        }
    } catch (error) {
        console.error('Erro:', error);
        alert("Erro ao tentar conectar com o servidor. Verifique se o XAMPP/Apache está rodando.");
    }
}