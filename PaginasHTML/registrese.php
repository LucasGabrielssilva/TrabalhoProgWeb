<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registre-se</title>
    <link rel="stylesheet" href="../FolhasCss/global.css">
    <link rel="stylesheet" href="../FolhasCss/registroEstilo.css">
</head>

<body>
    <header class="main-header">
        <h1>Jogo da Memória</h1>
    </header>

    <main>
        <form>
            <div id="mainDiv" class="form-group">
                <h2 class="form-title">Registre-se</h2>

                <label for="nome">Nome completo:</label>
                <input id="nome" name="nome" placeholder="Digite seu nome" size="40">

                <label for="datanasc">Data de Nascimento:</label>
                <input id="datanasc" name="datanasc" type="date">

                <label for="cpf">CPF:</label>
                <input id="cpf" name="cpf" placeholder="000.000.000-00" size="11">

                <label for="telefone">Telefone:</label>
                <input id="telefone" name="telefone" placeholder="(99) 99999-9999">

                <label for="email">Email:</label>
                <input id="email" name="email" placeholder="Digite seu email" size="40">

                <label for="usuario">Usuário:</label>
                <input id="usuario" name="usuario" placeholder="Digite seu usuário" size="20">

                <label for="senha">Senha:</label>
                <input id="senha" type="password" name="senha" placeholder="Digite sua senha" size="20">

                <label for="confirmasenha">Digite novamente sua senha:</label>
                <input id="confirmasenha" type="password" name="confirmasenha" placeholder="Digite novamente sua senha" size="20">

                <button type="submit" class="btn">Registrar</button>
                <a href="login.html">Já tem uma conta? Faça login!</a>
            </div>
        </form>
    </main>

    <footer class="main-footer">
        <p>&copy; 2025 Jogo da Memória</p>
    </footer>
    <script src="../JavaScript/cadastro.js"></script>
</body>

</html>
