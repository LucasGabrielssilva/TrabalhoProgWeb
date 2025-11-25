<?php require_once "../php/proteger.php"; ?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ranking Global - Jogo da Memória</title>
  <link rel="stylesheet" href="../FolhasCss/global.css">
  <link rel="stylesheet" href="../FolhasCss/RankingStyle.css">
</head>

<body>
  <header class="main-header">
    <h1>Ranking Global</h1>
    <nav>
      <a href="jogoMemoria.php">Voltar para o Jogo</a>
      <a href="editar-perfil.php">Editar Perfil</a>
      <a href="../php/logout.php">Desconectar</a>
    </nav>
  </header>

  <main class="container">
    <h2>Os 10 Melhores Jogadores</h2>
    <table class="data-table" id="ranking-table">
      <thead>
        <tr>
          <th>Posição</th>
          <th>Username</th>
          <th>Vitórias</th>
          <th>Jogadas</th>
          <th>Tempo Médio de Partida</th>
        </tr>
      </thead>
      <tbody>
        <!-- O ranking.js vai preencher aqui -->
      </tbody>
    </table>
  </main>

  <footer class="main-footer">
    <p>&copy; 2025 Jogo da Memória</p>
  </footer>

  <script src="../JavaScript/ranking.js"></script>
</body>

</html>
