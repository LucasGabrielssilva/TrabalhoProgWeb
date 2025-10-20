// --- SELEÇÃO DOS ELEMENTOS DO HTML ---
const tabuleiro = document.getElementById('tabuleiro');
const jogadasSpan = document.getElementById('jogadas');
const tempoSpan = document.getElementById('tempo');
const tamanhoSpan = document.getElementById('tamanho');
const verTrapacaBtn = document.getElementById('ver-trapaca');
const novoJogoBtn = document.getElementById('novo-jogo');

// --- ESTADO DO JOGO ---
let cartas = []; // Array de objetos que representa o jogo
let primeiraCarta = null;
let segundaCarta = null;
let travarTabuleiro = false; // Impede cliques durante a verificação
let jogadas = 0;
let paresEncontrados = 0;
let totalPares = 0;

// Símbolos para as cartas (pode ser alterado facilmente)
const EMOJIS = ['🐶', '🐱', '🐭', '🐹', '🐰', '🦊', '🐻', '🐼', '🐯', '🦁', '🐮', '🐷', '🐸', '🐵', '🐔', '🐧', '🐦', '🐤', '🐺', '🐗', '🐴', '🦄', '🐝', '🐛'];

// ==========================================================
// TAREFA 1: LÓGICA PRINCIPAL 
// ==========================================================

/**
 * Prepara e embaralha as cartas para o jogo.
 * @param {number} tamanho - A dimensão do tabuleiro (ex: 4 para 4x4).
 */
function embaralharCartas(tamanho) {
    totalPares = (tamanho * tamanho) / 2;
    const simbolosDoJogo = EMOJIS.slice(0, totalPares);
    const baralho = [...simbolosDoJogo, ...simbolosDoJogo]; // Duplica para formar pares

    // Algoritmo de embaralhamento Fisher-Yates
    for (let i = baralho.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [baralho[i], baralho[j]] = [baralho[j], baralho[i]];
    }
    cartas = baralho;
}

/**
 * Configura e inicia um novo jogo com o tamanho especificado.
 * @param {number} tamanho - A dimensão do tabuleiro.
 */
window.iniciarJogo = function(tamanho) {
    // Resetar variáveis
    jogadas = 0;
    paresEncontrados = 0;
    travarTabuleiro = false;
    primeiraCarta = null;
    segundaCarta = null;

    // TAREFA 2: Mostrar mensagem de "Jogo reiniciado!"
    if (document.querySelector('.carta')) { // Mostra a mensagem apenas se não for a primeira carga
        alert('Jogo reiniciado!');
    }

    // TAREFA 4 (Lógica integrada): Resetar contadores na interface
    jogadasSpan.textContent = '0';
    tamanhoSpan.textContent = `${tamanho}x${tamanho}`;
    // TAREFA 3 (Lógica integrada): Resetar cronômetro
    
    tempoSpan.textContent = '00:00';

    embaralharCartas(tamanho);
    renderizarTabuleiro(tamanho); 
}

/**
 * Verifica se as duas cartas viradas formam um par.
 */
function verificarPar() {
    const ehPar = primeiraCarta.dataset.value === segundaCarta.dataset.value;

    if (ehPar) {
        desabilitarCartas();
    } else {
        desvirarCartas();
    }
}

// ==========================================================
// TAREFA 2: INTERFACE DINÂMICA (Sua responsabilidade principal)
// ==========================================================

/**
 * Cria os elementos HTML das cartas e os exibe no tabuleiro.
 * @param {number} tamanho - A dimensão do tabuleiro para estilização.
 */
function renderizarTabuleiro(tamanho) {
    tabuleiro.innerHTML = '';
    tabuleiro.style.gridTemplateColumns = `repeat(${tamanho}, 1fr)`;

    cartas.forEach((simbolo, index) => {
        const cartaDiv = document.createElement('div');
        cartaDiv.classList.add('carta');
        cartaDiv.dataset.index = index; // Identificador único
        cartaDiv.dataset.value = simbolo; // O valor para comparação
        cartaDiv.textContent = '?'; // Começa virada para baixo
        cartaDiv.addEventListener('click', virarCarta); // Adiciona o evento de clique
        tabuleiro.appendChild(cartaDiv);
    });
}

/**
 * Lida com o clique em uma carta, implementando o efeito de virar.
 */
function virarCarta() {
    if (travarTabuleiro || this === primeiraCarta) return;

    // TAREFA 2: Implementa o efeito visual de virar a carta
    this.classList.add('virada');
    this.textContent = this.dataset.value;

    if (!primeiraCarta) {
        primeiraCarta = this;
        return;
    }

    segundaCarta = this;
    travarTabuleiro = true;

    // TAREFA 4 (Lógica integrada): Contabiliza a jogada
    jogadas++;
    jogadasSpan.textContent = jogadas;

    verificarPar();
}

/**
 * Mantém os pares corretos virados usando a classe CSS 'par-correto'.
 */
function desabilitarCartas() {
    primeiraCarta.removeEventListener('click', virarCarta);
    segundaCarta.removeEventListener('click', virarCarta);

    // TAREFA 2: Usa classe CSS para indicar o par correto
    primeiraCarta.classList.add('par-correto');
    segundaCarta.classList.add('par-correto');
    
    paresEncontrados++;
    resetarTurno();
    verificarFimDeJogo();
}

/**
 * Desvira as cartas erradas após um tempo, usando a classe CSS 'erro'.
 */
function desvirarCartas() {
    // TAREFA 2: Usa classe CSS para indicar o erro
    primeiraCarta.classList.add('erro');
    segundaCarta.classList.add('erro');

    setTimeout(() => {
        primeiraCarta.classList.remove('virada', 'erro');
        segundaCarta.classList.remove('virada', 'erro');
        primeiraCarta.textContent = '?';
        segundaCarta.textContent = '?';
        resetarTurno();
    }, 1200);
}

/**
 * Reseta as variáveis de controle do turno.
 */
function resetarTurno() {
    [primeiraCarta, segundaCarta, travarTabuleiro] = [null, null, false];
}

/**
 * Verifica se o jogo terminou e exibe a mensagem de vitória.
 */
function verificarFimDeJogo() {
    if (paresEncontrados === totalPares) {
        // TAREFA 3 (Lógica integrada): pararCronometro();
        setTimeout(() => {
            // TAREFA 2: Mostra a mensagem "Você venceu!"
            alert(`Você venceu em ${jogadas} jogadas!`);
        }, 500);
    }
}

/**
 * TAREFA 2: Função para o botão "Ver Trapaça".
 */
function ativarTrapaca() {
    const todasAsCartas = document.querySelectorAll('.carta');
    todasAsCartas.forEach(carta => {
        if (!carta.classList.contains('par-correto')) {
            carta.textContent = carta.dataset.value;
        }
    });
    // Desvira as cartas após um tempo para que a trapaça seja momentânea
    setTimeout(() => {
        todasAsCartas.forEach(carta => {
            if (!carta.classList.contains('virada')) {
                carta.textContent = '?';
            }
        });
    }, 2000);
}

// --- EVENT LISTENERS ---
document.addEventListener('DOMContentLoaded', () => {
    iniciarJogo(4); // Inicia o jogo com 4x4 por padrão
});

verTrapacaBtn.addEventListener('click', ativarTrapaca);
novoJogoBtn.addEventListener('click', () => {
    const tamanhoAtivo = document.querySelector('#seletor-tamanho button.ativo').dataset.tamanho;
    iniciarJogo(parseInt(tamanhoAtivo));
});

// Adiciona eventos aos botões de tamanho
document.querySelectorAll('#seletor-tamanho button').forEach(button => {
    button.addEventListener('click', () => {
        document.querySelector('#seletor-tamanho button.ativo').classList.remove('ativo');
        button.classList.add('ativo');
        iniciarJogo(parseInt(button.dataset.tamanho));
    });
});