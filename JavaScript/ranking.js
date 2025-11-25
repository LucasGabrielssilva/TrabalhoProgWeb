document.addEventListener('DOMContentLoaded', async () => {
    const tabela = document.getElementById('ranking-table').querySelector('tbody');

    try {
        const response = await fetch('../php/ranking.php');
        const ranking = await response.json();

        tabela.innerHTML = '';

        ranking.forEach(jogador => {
            const tr = document.createElement('tr');
            const tempoMin = Math.floor(jogador.tempo_medio / 60);
            const tempoSeg = Math.round(jogador.tempo_medio % 60);
            const tempoFormatado = `${tempoMin}m ${tempoSeg}s`;

            tr.innerHTML = `
                <td>${jogador.posicao}</td>
                <td>${jogador.username}</td>
                <td>${jogador.vitorias}</td>
                <td>${jogador.total_jogadas}</td>
                <td>${tempoFormatado}</td>
            `;

            tabela.appendChild(tr);
        });

    } catch (error) {
        console.error("Erro ao carregar ranking:", error);
        tabela.innerHTML = `<tr><td colspan="5">Erro ao carregar ranking</td></tr>`;
    }
});
