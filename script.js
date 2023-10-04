const { createApp } = Vue


const microphone = document.getElementById('microphone');
const audioContext = new (window.AudioContext || window.webkitAudioContext)();
const audioChunks = [];

let isRecording = false;
let recognition = null;
let isSpeaking = false;

createApp({
    data() {
        return {
            loaded: true,
            result: '',
            synthesis: false,
            isRecording: false,
            recognition: false,
            isSpeaking: false,
            sending: false,
            backendUrl: '/api.php',
        }
    },
    mounted() {
        window.speechSynthesis.cancel()
    },
    methods: {
        start() {
            if (this.isSpeaking) {
                this.synthesis.cancel(); // Pare a fala se estiver ocorrendo
                this.stopRecording();
            } else {
                if (this.isRecording) {
                    this.stopRecording();
                } else {
                    this.startRecording();
                }
            }
        },
        startRecording() {
            this.isRecording = true;
            audioChunks.length = 0;
            this.recognition = new webkitSpeechRecognition();
            this.recognition.lang = 'pt-BR';
            this.recognition.continuous = false; // Permite o reconhecimento contínuo
            this.recognition.interimResults = false; // Mostra resultados finais
            this.recognition.onresult = (event) => {
                this.result = event.results[event.results.length - 1][0].transcript;
            };
            this.recognition.onstart = () => {
                console.log('Iniciando o reconhecimento de fala.');
            };
            this.recognition.onend = () => {
                // Remove a classe 'active' para parar de exibir a cor vermelha
                this.stopRecording();
                this.sendTextToBackend(this.result);
            };
            this.recognition.start();
        },
        stopRecording() {
            this.isRecording = false;
            this.isSpeaking = false;
            this.recognition.stop();
        },
        convertTextToSpeech(text) {
            // Verifica se o navegador suporta a síntese de fala
            if ('speechSynthesis' in window) {
                if (this.isSpeaking) {
                    this.synthesis.cancel();
                    this.isSpeaking = false;
                }
                // Cria um novo objeto de síntese de fala
                this.synthesis = window.speechSynthesis;

                // Cria uma nova instância de fala
                const utterance = new SpeechSynthesisUtterance(text);

                // Define a voz que será usada (opcional)
                const voices = this.synthesis.getVoices();
                utterance.voice = voices[0]; // Escolha a voz desejada

                // Configura outras opções, como velocidade e volume (opcional)
                utterance.rate = 1.9; // Velocidade da fala (0.1 a 10)
                utterance.volume = 1.0; // Volume da fala (0 a 1)

                // Evento disparado quando a fala inicia
                utterance.onstart = () => {
                    this.isSpeaking = true;
                };

                // Evento disparado quando a fala termina
                utterance.onend = () => {
                    this.isSpeaking = false;
                    this.isRecording = false;
                };

                // Inicia a síntese de fala
                this.synthesis.speak(utterance);
            } else {
                console.error('A síntese de fala não é suportada neste navegador.');
            }
        },
        sendTextToBackend(texto) {
            // Realiza uma solicitação Fetch para obter o texto do backend
            this.sending = true
            fetch(this.backendUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ texto }), // Envia o texto para o backend
            })
                .then((response) => {
                    if (!response.ok) {
                        throw new Error('Erro na solicitação ao backend');
                    }
                    return response.text(); // Obtém o texto da resposta
                })
                .then((textFromBackend) => {
                    // Chama a função para converter texto em fala com o texto obtido do backend
                    this.result = ''
                    this.convertTextToSpeech(textFromBackend);
                    this.sending = false
                })
                .catch((error) => {
                    this.result = ''
                    console.error('Erro:', error);
                    this.sending = false
                });
        }
    }
}).mount('#assistente')