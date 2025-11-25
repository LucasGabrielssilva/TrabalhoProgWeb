document.querySelector('form').addEventListener('submit', function(event) {
    event.preventDefault();

    const senha = document.getElementById('senha').value;
    const confirmaSenha = document.getElementById('confirmasenha').value;

    if (senha !== confirmaSenha) {
        alert('As senhas digitadas não coincidem.');
        return;
    }

    const formData = new FormData(this);

    fetch('../php/cadastro.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.sucesso) {
            alert('✅ Cadastro realizado com sucesso! ' + data.mensagem);
            window.location.href = 'login.html';
        } else {
            alert('❌ Erro no cadastro: ' + data.mensagem);
        }
    })
    .catch(() => {
        alert('Ocorreu um erro ao tentar conectar com o servidor. Tente novamente.');
    });
});
