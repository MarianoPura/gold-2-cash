<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gold For Cash - WINNER!</title>
    <style>
        @import url('fonts/fonts.css');

        body {
            background-color: #000c3b;
            background-image: radial-gradient(circle at center, #003181 0%, #000c3b 100%);
            text-align: center;
            font-family: 'Playfair Display', serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
        }

        #bg-canvas {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            pointer-events: none;
            opacity: 0.75;
        }

        #canvas {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
            pointer-events: none;
        }

        .chest-coins {
            width: 100vw;
            height: 30%;
        }

        .chest-coins {
            content: url('./assets/chest-coins-2.png');
            display: inline-block;
        }

        .casino-board {
            border: 18px solid #ffd700;
            border-radius: 20px;
            padding: 50px 50px;
            background: linear-gradient(135deg, #003181, #000c3b);
            position: relative;
            z-index: 10;
            animation: boardFlash 2.5s infinite alternate;
            margin-bottom: 250px;
        }

        #title {
            font-family: 'FuturaCyrillicBold', sans-serif;
            font-size: 6rem;
            text-transform: uppercase;
            background: linear-gradient(to bottom, #fff7a1, #ffd700, #b8860b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: 4px;
        }


        #weight-container::before {
            content: 'WEIGHT';
            position: absolute;
            top: -14px;
            left: 50%;
            transform: translateX(-50%);
            background: #111;
            border: 2px solid #b8860b;
            border-radius: 5px;
            padding: 2px 15px;
            font-size: 14px;
            color: #ffd700;
            font-family: 'Arial', sans-serif;
            letter-spacing: 3px;
            font-weight: bold;
        }

        #weight {
            font-family: 'Orbitron', sans-serif;
            font-size: 8rem;
            letter-spacing: 5px;
            margin: 0;
            line-height: 1;
            color: #ffffff;
            text-shadow: 0 0 30px #ffffff, 0 0 60px #ffd700, 0 0 90px #ffae00;
        }

        .unit {
            font-family: 'Arial', sans-serif;
            font-size: 2rem;
            vertical-align: super;
            margin-left: 10px;
            color: #ffd700;
        }



        @keyframes boardFlash {
            0% {
                box-shadow: 0 0 40px #b8860b, inset 0 0 30px #b8860b;
                border-color: #b8860b;
            }

            50% {
                box-shadow: 0 0 80px #fff7a1, inset 0 0 50px #fff7a1;
                border-color: #fff7a1;
            }

            100% {
                box-shadow: 0 0 60px #ffd700, inset 0 0 40px #ffd700;
                border-color: #ffd700;
            }

            0% {
                box-shadow: 0 0 40px #b8860b, inset 0 0 30px #b8860b;
                border-color: #b8860b;
            }

            50% {
                box-shadow: 0 0 80px #fff7a1, inset 0 0 50px #fff7a1;
                border-color: #fff7a1;
            }

            100% {
                box-shadow: 0 0 60px #ffd700, inset 0 0 40px #ffd700;
                border-color: #ffd700;
            }
        }


        @keyframes titleJackpot {
            0% {
                text-shadow: 0 0 20px #ffd700;
            }

            100% {
                text-shadow: 0 0 40px #fff, 0 0 80px #ffd700, 0 0 120px #ffae00;
            }
        }

        @keyframes slotWin {
            0% {
                transform: translateY(0);
                box-shadow: inset 0 0 30px rgba(255, 215, 0, 0.4);
            }

            50% {
                transform: translateY(-4px);
                box-shadow: inset 0 0 60px rgba(255, 215, 0, 0.9);
            }

            100% {
                transform: translateY(0);
                box-shadow: inset 0 0 30px rgba(255, 215, 0, 0.4);
            }

            0% {
                transform: translateY(0);
                box-shadow: inset 0 0 30px rgba(255, 215, 0, 0.4);
            }

            50% {
                transform: translateY(-4px);
                box-shadow: inset 0 0 60px rgba(255, 215, 0, 0.9);
            }

            100% {
                transform: translateY(0);
                box-shadow: inset 0 0 30px rgba(255, 215, 0, 0.4);
            }
        }

        @media (max-width: 1100px) {
            .chest-coins {
                content: url('./assets/chest-coins.png');
            }


            .casino-board {
                padding: 10px 10px;
                border-width: 18px;
                width: 90vw;
                box-sizing: border-box;
                margin-bottom: 150px;
            }


            #title {
                font-size: clamp(5.5rem, 12vw, 10rem);
                line-height: 80px;
            }


            #weight {
                font-size: clamp(4rem, 18vw, 8rem);
            }


            .unit {
                font-size: 1.5rem;
                margin-left: 5px;
            }

            .chest-coins {
                width: 100vw;
                height: auto;
            }
        }

        @media (max-width: 900px) {
            .chest-coins {
                content: url('./assets/chest-coins-mobile.png');
            }
        }
    </style>
</head>

<body>
    <video id="bg-video" src="./assets/raining-coin.mp4"
        style="object-fit: cover; mix-blend-mode: screen; position: absolute; top: 0; left: 0; height: 100vh; width: 100%;"
        autoplay loop muted playsinline></video>
    <canvas id="bg-canvas"></canvas>

    <canvas id="canvas"></canvas>

    <div class="casino-board">
        <h1 id="title" style="letter-spacing: normal;">WE HAVE A JACKPOT WINNER!</h1>
    </div>

    <div><img class="chest-coins"
            style="filter: drop-shadow(0px 0px 32px #ffd700); position: absolute; bottom: 0; left: 0;"
            src="./assets/chest-coins.png" alt=""></div>

    <script>
        function updateWeight(val) {
            let newVal = parseFloat(val);
            if (isNaN(newVal)) return;

            if (targetWeight !== newVal) {
                targetWeight = newVal;
                currentWeight = 0;
                if (!isAnimatingWeight) {
                    animateWeightDisplay();
                }
            }
        }

        let currentWeight = 0;
        let targetWeight = 0;
        let isAnimatingWeight = false;
        let currentWeight = 0;
        let targetWeight = 0;
        let isAnimatingWeight = false;

        function animateWeightDisplay() {
            let diff = targetWeight - currentWeight;

            if (Math.abs(diff) < 1) {
                currentWeight = targetWeight;
                document.getElementById("weight").innerText = currentWeight.toFixed(0);
                isAnimatingWeight = false;
                return;
            }

            isAnimatingWeight = true;
            currentWeight += diff * 0.03;
            document.getElementById("weight").innerText = currentWeight.toFixed(0);

            requestAnimationFrame(animateWeightDisplay);
        }

        setInterval(() => {
            fetch("weight.php")
                .then(r => r.text())
                .then(t => updateWeight(t))
                .catch(e => console.log(e));
        }, 200);

        setInterval(() => {
            fetch("winner.php")
                .then(r => r.text())
                .then(t => {
                    if (t.trim() != "1") {
                        window.location.reload();
                    }
                })
                .catch(e => console.log(e));
        }, 200);
    </script>
</body>

</html>