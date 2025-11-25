async function login(usuario, senha) {
    if (!usuario || !senha) {
        alert("Por favor, preencha usuário e senha.");
        return;
    }

    try {
        const response = await fetch('../php/login.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ username: usuario, senha: senha })
        });

        const data = await response.json();

        if (data.success) {
            window.location.href = "jogoMemoria.php";
        } else {
            alert(data.message);
        }
    } catch (error) {
        console.error('Erro:', error);
        alert("Erro ao tentar conectar com o servidor.");
    }
}
