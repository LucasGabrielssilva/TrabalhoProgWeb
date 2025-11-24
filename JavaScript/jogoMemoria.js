const tabuleiro = document.getElementById('tabuleiro');
const jogadasSpan = document.getElementById('jogadas');
const tempoSpan = document.getElementById('tempo');
const tamanhoSpan = document.getElementById('tamanho');
const verTrapacaBtn = document.getElementById('ver-trapaca');
const novoJogoBtn = document.getElementById('novo-jogo');
const modoSpan = document.getElementById('modo');

let cartas = [];
let primeiraCarta = null;
let segundaCarta = null;
let travarTabuleiro = false;
let jogadas = 0;
let paresEncontrados = 0;
let totalPares = 0;
let tamanhoAtual = 4;
let modoAtual = 'Clássico';

let segundos = 0;
let intervaloTempo = null;
let tempoLimite = 120;

function iniciarCronometro() {
    clearInterval(intervaloTempo);

    if (modoAtual === 'Clássico') {
        segundos = 0;
        tempoSpan.textContent = '00:00';

        intervaloTempo = setInterval(() => {
            segundos++;
            const minutos = String(Math.floor(segundos / 60)).padStart(2, '0');
            const segundosFormatados = String(segundos % 60).padStart(2, '0');
            tempoSpan.textContent = `${minutos}:${segundosFormatados}`;
        }, 1000);

    } else if (modoAtual === 'Contra o Tempo') {
        segundos = tempoLimite;

        intervaloTempo = setInterval(() => {
            const minutos = String(Math.floor(segundos / 60)).padStart(2, '0');
            const segundosFormatados = String(segundos % 60).padStart(2, '0');
            tempoSpan.textContent = `${minutos}:${segundosFormatados}`;

            if (segundos <= 0) {
                clearInterval(intervaloTempo);
                alert('⏱️ Tempo esgotado! Você perdeu.');
                desabilitarTodasAsCartas();
                return;
            }

            segundos--;
        }, 1000);
    }
}

function pararCronometro() {
    clearInterval(intervaloTempo);
}

function desabilitarTodasAsCartas() {
    const todas = document.querySelectorAll('.carta');
    todas.forEach(carta => carta.removeEventListener('click', virarCarta));
}

const EMOJIS = ['🐶', '🐱', '🐭', '🐹', '🐰', '🦊', '🐻', '🐼', '🐯', '🦁', '🐮', '🐷', '🐸', '🐵', '🐔', '🐧', '🐦', '🐤', '🐺', '🐗', '🐴', '🦄', '🐝', '🐛'];

function incrementarJogada() {
    jogadas++;
    jogadasSpan.textContent = jogadas;
}

function resetarJogadas() {
    jogadas = 0;
    jogadasSpan.textContent = jogadas;
}

function embaralharCartas(tamanho) {
    totalPares = (tamanho * tamanho) / 2;
    const simbolosDoJogo = EMOJIS.slice(0, totalPares);
    const baralho = [...simbolosDoJogo, ...simbolosDoJogo];

    for (let i = baralho.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [baralho[i], baralho[j]] = [baralho[j], baralho[i]];
    }
    cartas = baralho;
}

function renderizarTabuleiro(tamanho) {
    tabuleiro.innerHTML = '';
    tabuleiro.style.gridTemplateColumns = `repeat(${tamanho}, 1fr)`;

    cartas.forEach((simbolo, index) => {
        const cartaDiv = document.createElement('div');
        cartaDiv.classList.add('carta');
        cartaDiv.dataset.index = index;
        cartaDiv.dataset.value = simbolo;
        cartaDiv.textContent = '?';
        cartaDiv.addEventListener('click', virarCarta);
        tabuleiro.appendChild(cartaDiv);
    });
}

function virarCarta() {
    if (travarTabuleiro || this === primeiraCarta) return;

    this.classList.add('virada');
    this.textContent = this.dataset.value;

    if (!primeiraCarta) {
        primeiraCarta = this;
        return;
    }

    segundaCarta = this;
    travarTabuleiro = true;

    incrementarJogada();
    verificarPar();
}

function verificarPar() {
    const ehPar = primeiraCarta.dataset.value === segundaCarta.dataset.value;

    if (ehPar) {
        desabilitarCartas();
    } else {
        desvirarCartas();
    }
}

function desabilitarCartas() {
    primeiraCarta.removeEventListener('click', virarCarta);
    segundaCarta.removeEventListener('click', virarCarta);

    primeiraCarta.classList.add('par-correto');
    segundaCarta.classList.add('par-correto');

    paresEncontrados++;
    resetarTurno();
    verificarFimDeJogo();
}

function desvirarCartas() {
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

function resetarTurno() {
    [primeiraCarta, segundaCarta, travarTabuleiro] = [null, null, false];
}

function verificarFimDeJogo() {
    if (paresEncontrados === totalPares) {
        pararCronometro();
        setTimeout(() => {
            alert(`🎉 Você venceu em ${jogadas} jogadas!`);
            iniciarJogo(tamanhoAtual);
        }, 500);
    }
}

function ativarTrapaca() {
    const todasAsCartas = document.querySelectorAll('.carta');
    todasAsCartas.forEach(carta => {
        if (!carta.classList.contains('par-correto')) {
            carta.textContent = carta.dataset.value;
        }
    });
    setTimeout(() => {
        todasAsCartas.forEach(carta => {
            if (!carta.classList.contains('virada')) {
                carta.textContent = '?';
            }
        });
    }, 2000);
}

window.iniciarJogo = function (tamanho) {
    tamanhoAtual = tamanho;

    jogadas = 0;
    paresEncontrados = 0;
    travarTabuleiro = false;
    primeiraCarta = null;
    segundaCarta = null;

    if (document.querySelector('.carta')) {
        alert('Jogo reiniciado!');
    }

    resetarJogadas();
    pararCronometro();
    iniciarCronometro();

    embaralharCartas(tamanho);
    renderizarTabuleiro(tamanho);
}
async function salvarPartida(tempo, tentativas, pares, modo, tabuleiro) {
    // Prepara os dados como se fosse um formulário HTML
    const formData = new FormData();
    formData.append('tempo', tempo);         // Ex: 120 (segundos)
    formData.append('tentativas', tentativas); // Ex: 15
    formData.append('pares', pares);         // Ex: 8
    formData.append('modo', modo);           // Ex: 'difícil'
    formData.append('tabuleiro', tabuleiro); // Ex: '4x4'

    try {
        const response = await fetch('php/registrar.php', { // Ajuste o caminho se necessário
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (data.sucesso) {
            console.log("Partida salva!", data.mensagem);
            alert("Parabéns! Seu resultado foi salvo.");
        } else {
            console.error("Erro ao salvar:", data.mensagem);
            // Se o erro for "Login necessário", redirecionar
            if(response.status === 401 || response.status === 403) {
                window.location.href = 'login.html';
            }
        }
    } catch (error) {
        console.error("Erro na requisição:", error);
    }
}

window.setModoJogo = function (modo) {
    modoAtual = modo;
    modoSpan.textContent = modo;

    document.querySelectorAll('#seletor-modo button').forEach(btn => {
        btn.classList.remove('ativo');
    });

    if (modo === 'Clássico') {
        document.getElementById('btn-modo-classico').classList.add('ativo');
    } else if (modo === 'Contra o Tempo') {
        document.getElementById('btn-modo-tempo').classList.add('ativo');
    }

    iniciarJogo(tamanhoAtual);
};

document.addEventListener('DOMContentLoaded', () => {
    iniciarJogo(4);
});

verTrapacaBtn.addEventListener('click', ativarTrapaca);
novoJogoBtn.addEventListener('click', () => {
    iniciarJogo(tamanhoAtual);
});

document.querySelectorAll('#seletor-tamanho button').forEach(button => {
    button.addEventListener('click', () => {
        document.querySelector('#seletor-tamanho button.ativo')?.classList.remove('ativo');
        button.classList.add('ativo');
        iniciarJogo(parseInt(button.dataset.tamanho));
    });
});
