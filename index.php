<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Conversor de Fala</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f0f0;
        }

        #microphone {
            width: 200px;
            height: 200px;
            background-color: #fff;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        #microphone.active {
            background-color: red;
            border: 2px solid white;
            animation: pulse 1s infinite;
        }
        #microphone.active svg {
            fill: #fff;
        }

        #microphone.disabled {
            opacity: 0.5;
        }

        #microphone.active.resposta {
            background-color: blue;
        }
        svg {
            width: 100px
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }

            100% {
                transform: scale(1);
            }
        }
        button.disabled {
            pointer-events: none;
        }
        button:not(.active) {
            border: 2px solid #222;
        }
        button:not(.loaded) {
            display: none
        }
    </style>
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
</head>

<body>
    <div id="assistente">
        <button type="button" @click="start" id="microphone" :class="{loaded: loaded, active: isRecording || isSpeaking, resposta: isSpeaking, disabled: sending}">
            <svg v-if="!sending" xmlns="http://www.w3.org/2000/svg" height="3em"
            viewBox="0 0 384 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                <path
                d="M192 0C139 0 96 43 96 96V256c0 53 43 96 96 96s96-43 96-96V96c0-53-43-96-96-96zM64 216c0-13.3-10.7-24-24-24s-24 10.7-24 24v40c0 89.1 66.2 162.7 152 174.4V464H120c-13.3 0-24 10.7-24 24s10.7 24 24 24h72 72c13.3 0 24-10.7 24-24s-10.7-24-24-24H216V430.4c85.8-11.7 152-85.3 152-174.4V216c0-13.3-10.7-24-24-24s-24 10.7-24 24v40c0 70.7-57.3 128-128 128s-128-57.3-128-128V216z" />
            </svg>
            <img v-else src="./Infinity-1s-200px.svg" width="100" alt="">
        </button>
    </div>
    <script async src="./script.js?<?php echo time(); ?>"></script>
</body>

</html>