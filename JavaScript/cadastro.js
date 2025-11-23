document.querySelector('form').addEventListener('submit', function(event) {
    // 1. Previne o envio padrão do formulário (que recarregaria a página)
    event.preventDefault(); 

    const senha = document.getElementById('senha').value;
    const confirmaSenha = document.getElementById('confirmasenha').value;
    
    // 2. Validação simples de Senha (Front-end)
    if (senha !== confirmaSenha) {
        alert('As senhas digitadas não coincidem.');
        return; // Para o processo
    }

    // 3. Coleta todos os dados do formulário
    const formData = new FormData(this);
    
    // O seu PHP espera: nome, datanasc, cpf, telefone, email, usuario, senha
    // Certifique-se de que os nomes no HTML (name="") são exatamente o que o PHP espera!
    
    // 4. Envia os dados para o endpoint PHP usando Fetch API
    // AJUSTE O CAMINHO: Presumindo que o PHP está em "../php/cadastro.php"
    fetch('../php/cadastro.php', { 
        method: 'POST',
        body: formData // Envia os dados coletados
    })
    .then(response => {
        // Checa se a resposta é um erro de servidor (HTTP 4xx ou 5xx)
        if (!response.ok) {
            throw new Error('Erro de rede ou servidor: ' + response.statusText);
        }
        return response.json(); // Processa a resposta JSON do PHP
    })
    .then(data => {
        // 5. Processa a resposta do Back-end
        if (data.sucesso) {
            alert('✅ Cadastro realizado com sucesso! ' + data.mensagem);
            // Redireciona para a página de login
            window.location.href = 'login.html'; 
        } else {
            alert('❌ Erro no cadastro: ' + data.mensagem);
        }
    })
    .catch(error => {
        // Trata erros de rede ou exceptions lançadas
        console.error('Falha na comunicação:', error);
        alert('Ocorreu um erro ao tentar conectar com o servidor. Tente novamente.');
    });
});
