<?php require_once "../php/proteger.php"; ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="../FolhasCss/global.css">
  <link rel="stylesheet" href="../FolhasCss/stilohistorico.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>historico</title>
</head>

<body>
  <header class="main-header">
    <h1>Jogo da Memória</h1>
    <nav>
      <a href="jogoMemoria.php">Voltar para o jogo</a>
      <a href="editar-perfil.php">Editar Perfil</a>
      <a href="../php/logout.php">Desconectar</a>
    </nav>
  </header>

  <main class="container">
    <h2>Partidas Recentes</h2>
    <table class="data-table" id="historico-table">
      <thead>
        <tr>
          <th>Nome do Jogador</th>
          <th>Tabuleiro</th>
          <th>Tempo</th>
          <th>Nº de Jogadas</th>
          <th>Resultado</th>
        </tr>
      </thead>
      <tbody id="historico-table-body">
      </tbody>
    </table>
  </main>

  <footer class="main-footer">
    <p class="muted">© 2025 Jogo da Memória</p>
  </footer>

  <script src="../JavaScript/historico.js"></script>
</body>

</html>