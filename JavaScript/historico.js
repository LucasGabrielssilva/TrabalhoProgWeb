async function carregarHistorico() {
    try {
        const response = await fetch('../php/historico.php');
        const historico = await response.json();

        const tbody = document.getElementById('historico-table-body');
        tbody.innerHTML = '';

        historico.forEach(jogo => {
            const tr = document.createElement('tr');

            const minutos = String(Math.floor(jogo.tempo_segundos / 60)).padStart(2, '0');
            const segundos = String(jogo.tempo_segundos % 60).padStart(2, '0');
            const tempoFormatado = `${minutos}:${segundos}`;

            const totalPares = (parseInt(jogo.tamanho_tabuleiro) ** 2) / 2;
            const resultado = (jogo.jogadas > 0 && totalPares > 0) ? 'Vitória' : 'Derrota';
            const classeResultado = resultado.toLowerCase();

            tr.innerHTML = `
                <td>${jogo.nome}</td>
                <td>${jogo.tamanho_tabuleiro}</td>
                <td>${tempoFormatado}</td>
                <td>${jogo.jogadas}</td>
                <td class="${classeResultado}">${resultado}</td>
            `;

            tbody.appendChild(tr);
        });
    } catch {
        console.error('Erro ao carregar histórico');
    }
}

document.addEventListener('DOMContentLoaded', carregarHistorico);
