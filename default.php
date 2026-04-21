<!DOCTYPE html>
<html>


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gold For Cash</title>
    <style>
        @import url('fonts/fonts.css');

        body {
            background-color: #000c3b;
            background-image: radial-gradient(circle at center, #003181 0%, #000c3b 100%);
            color: #fff;
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
            padding: 50px 80px;
            background: linear-gradient(135deg, #003181, #000c3b);
            box-shadow: 0 0 40px #ffd700, inset 0 0 20px #000;
            position: relative;
            z-index: 10;
            animation: boardBreathe 4s ease-in-out infinite alternate;
            margin-bottom: 250px;
        }

        #title {
            font-family: 'FuturaCyrillicBold', sans-serif;
            font-size: 5rem;
            margin-bottom: 40px;
            margin-top: 0;
            text-transform: uppercase;
            background: linear-gradient(to bottom, #fef4b7, #e9ca4c, #fef4b7);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 0 20px rgba(255, 215, 0, 0.4);
            letter-spacing: 4px;
            animation: titleBreathe 3s ease-in-out infinite alternate;
        }

        #weight-container {
            background: linear-gradient(135deg, #003181, #000c3b);
            border: 4px solid #b8860b;
            border-radius: 10px;
            padding: 30px 60px;
            display: inline-block;
            box-shadow: inset 0 0 30px rgba(255, 215, 0, 0.2), 0 5px 15px rgba(0, 0, 0, 0.8);
            box-shadow: inset 0 0 30px rgba(255, 215, 0, 0.2), 0 5px 15px rgba(0, 0, 0, 0.8);
            position: relative;
        }

        #weight-container::before {
            content: 'WEIGHT';
            position: absolute;
            top: -14px;
            left: 50%;
            transform: translateX(-50%);
            background: #000c3b;
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
            color: #ffda44;
            text-shadow: 0 0 20px rgba(255, 215, 0, 0.8);
            letter-spacing: 5px;
            margin: 0;
            line-height: 1;
            animation: ledFlicker 6s infinite;
        }

        .unit {
            font-family: 'Arial', sans-serif;
            font-weight: bold;
            font-size: 4rem;
            color: #a38914;
            vertical-align: super;
            text-shadow: none;
            margin-left: 10px;
        }

        @keyframes boardBreathe {
            0% {
                box-shadow: 0 0 20px #ffd700, inset 0 0 10px #000;
                border-color: #ccaa00;
            }

            100% {
                box-shadow: 0 0 50px #ffea00, inset 0 0 30px #000;
                border-color: #ffea33;
            }

            0% {
                box-shadow: 0 0 20px #ffd700, inset 0 0 10px #000;
                border-color: #ccaa00;
            }

            100% {
                box-shadow: 0 0 50px #ffea00, inset 0 0 30px #000;
                border-color: #ffea33;
            }
        }

        @keyframes titleBreathe {
            0% {
                text-shadow: 0 0 10px rgba(255, 215, 0, 0.3);
            }

            100% {
                text-shadow: 0 0 30px rgba(255, 215, 0, 0.7);
            }

            0% {
                text-shadow: 0 0 10px rgba(255, 215, 0, 0.3);
            }

            100% {
                text-shadow: 0 0 30px rgba(255, 215, 0, 0.7);
            }
        }

        @keyframes ledFlicker {

            0%,
            19.999%,
            22%,
            62.999%,
            64%,
            64.999%,
            70%,
            100% {
                opacity: 1;
                text-shadow: 0 0 20px rgba(255, 215, 0, 0.8);
            }

            20%,
            21.999%,
            63%,
            63.999%,
            65%,
            69.999% {
                opacity: 0.85;
                text-shadow: 0 0 5px rgba(255, 215, 0, 0.3);
            }

            0%,
            19.999%,
            22%,
            62.999%,
            64%,
            64.999%,
            70%,
            100% {
                opacity: 1;
                text-shadow: 0 0 20px rgba(255, 215, 0, 0.8);
            }

            20%,
            21.999%,
            63%,
            63.999%,
            65%,
            69.999% {
                opacity: 0.85;
                text-shadow: 0 0 5px rgba(255, 215, 0, 0.3);
            }
        }

        @media (max-width: 1100px) {
            .chest-coins {
                content: url('./assets/chest-coins.png');
            }

            .casino-board {
                padding: 40px 20px;
                border-width: 18px;
                width: 90vw;
                box-sizing: border-box;
                margin-bottom: 150px;
            }


            #title {
                font-size: clamp(3rem, 12vw, 5rem);
                margin-bottom: 30px;
                line-height: -30px;
            }


            #weight-container {
                padding: 20px 15px;
                box-sizing: border-box;
                width: 100%;
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
    <canvas id="canvas"></canvas>

    <div class="casino-board">
        <h1 id="title" style="letter-spacing: normal;">Gold For Cash</h1>
        <div id="weight-container">
            <span id="weight">0</span><span class="unit">g</span>
        </div>
    </div>
    <div><img class="chest-coins"
            style="filter: drop-shadow(0px 0px 32px #ffd700); position: absolute; bottom: 0; left: 0;"
            src="./assets/chest-coins.png" alt="">
    </div>

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
                    if (t.trim() == "1") {
                        window.location.reload();
                    }
                })
                .catch(e => console.log(e));
        }, 200);

        const canvas = document.getElementById("canvas");
        const ctx = canvas.getContext("2d");
        let cw = window.innerWidth;
        let ch = window.innerHeight;
        canvas.width = cw;
        canvas.height = ch;

        window.addEventListener("resize", () => {
            cw = window.innerWidth;
            ch = window.innerHeight;
            canvas.width = cw;
            canvas.height = ch;
        });

        function random(min, max) {
            return Math.random() * (max - min) + min;
        }

        let ambientDust = [];
        for (let i = 0; i < 40; i++) {
            ambientDust.push({
                x: random(0, cw),
                y: random(0, ch),
                r: random(4, 6.5),
                vy: random(-0.8, -0.2),
                vx: random(-0.3, 0.3),
                opacity: random(0.1, 0.6)
            });
        }

        function animateDust() {
            requestAnimationFrame(animateDust);
            ctx.clearRect(0, 0, cw, ch);

            ambientDust.forEach(p => {
                p.y += p.vy;
                p.x += p.vx;

                if (p.y < -10) p.y = ch + 10;
                if (p.x < -10) p.x = cw + 10;
                if (p.x > cw + 10) p.x = -10;

                ctx.beginPath();
                ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
                ctx.fillStyle = `rgba(255, 215, 0, ${p.opacity})`;
                ctx.fill();
            });
        }

        animateDust();
    </script>
</body>

</html>