<!DOCTYPE html>
<html>


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gold For Cash</title>
    <style>
        @import url('fonts/fonts.css');

        body {
            /* background-color: #000c3b; */
            background-image: url('./assets/bg-blue.png');
            background-size: cover;
            background-position: center;
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
            content: url('./assets/okada-building-mobile.png');
        }

        .casino-board {
            background: url('./assets/circle-background.png') no-repeat center;
            background-size: contain;
            width: clamp(300px, 80vw, 500px);
            aspect-ratio: 1 / 1;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            margin: 0 auto;
        }

        #title {
            font-family: 'FuturaCyrillicBold', sans-serif;
            font-size: 6rem;
            margin-bottom: 15px;
            margin-top: 0;
            text-transform: uppercase;
            background: linear-gradient(to bottom, #fef4b7, #e9ca4c, #fef4b7);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 0 20px rgba(255, 215, 0, 0.4);
            letter-spacing: 2px;
            line-height: 100px;
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
            font-size: 6rem;
            color: #ffda44;
            text-shadow: 0 0 20px rgba(255, 215, 0, 0.8);
            letter-spacing: 5px;
            margin: 0;
            line-height: 1;
            animation: ledFlicker 6s infinite;
        }

        .gram-number {
            background: rgba(5, 84, 210, 0.6);
            border: 3px solid #e9ca4c;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5),
                0 0 20px rgba(233, 202, 76, 0.2),
                inset 0 0 15px rgba(0, 0, 0, 0.8);
            border-radius: 12px;
            height: auto;
            min-height: 120px;
            width: clamp(300px, 80vw, 400px);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 10px 20px;
        }

        .unit-badge {
            margin-top: 20px;
            display: inline-block;
            padding: 10px 20px;
            position: relative;
            overflow: hidden;
            z-index: 100;
        }

        .unit-badge::after {
            content: '';
            position: absolute;
            top: 0;
            left: -150%;
            width: 80%;
            height: 100%;
            transform: skewX(-25deg);
            animation: unitShine 4s infinite ease-in-out;
        }

        .top-logo {
            position: absolute;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: auto;
            padding: 0;
            margin: 0;
        }

        .gold-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 90%;
            height: auto;
            z-index: 10;
        }

        @keyframes unitShine {
            0% {
                left: -150%;
            }

            20% {
                left: 150%;
            }

            100% {
                left: 150%;
            }
        }

        .unit {
            border: 3px solid #e9ca4c;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5),
                0 0 20px rgba(233, 202, 76, 0.2),
                inset 0 0 15px rgba(0, 0, 0, 0.8);
            border-radius: 12px;
            padding: 10px 10px;
            position: relative;
            top: -20px;
            display: inline-block;
            background: linear-gradient(to bottom, #fef4b7, #e9ca4c, #fef4b7);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-family: 'FuturaCyrillicBold', sans-serif;
            font-size: 3rem;
            font-weight: 700;
            margin: 0;
            letter-spacing: 4px;
            text-transform: uppercase;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.5));
            line-height: 1;
            z-index: 1;
        }

        .unit::after {
            border-radius: 10px;
            background: rgba(5, 84, 210, 0.6);
            padding: 10px 10px;
            position: absolute;
            top: 0;
            left: 0;
            content: attr(data-text);
            text-transform: uppercase;
            font-family: 'FuturaCyrillicBold', sans-serif;
            font-size: 3rem;
            font-weight: 900;
            letter-spacing: 4px;
            line-height: 1;
            z-index: -1;
            -webkit-text-fill-color: transparent;
            color: transparent;

            text-shadow:
                0 -2px 0 #ffe87a,
                -2px 0 0 #d4a800,
                2px 0 0 #d4a800,
                0 2px 0 #7a5500,
                -1px -1px 0 #f0c800,
                1px -1px 0 #f0c800,
                -1px 1px 0 #a07a10,
                1px 1px 0 #a07a10;
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
                height: 200px;
                content: url('./assets/okada-building-mobile-original.png');
            }

            .casino-board {
                border-width: 18px;
                width: clamp(300px, 85vw, 450px);
                box-sizing: border-box;
            }

            .gold-text {
                width: 85%;
                top: 50%;
            }


            #title {
                font-size: clamp(5rem, 10vw, 8rem);
                margin-bottom: 30px;
                line-height: 70px;
            }


            #weight-container {
                padding: 15px 15px;
                box-sizing: border-box;
                width: 100%;
            }


            #weight {
                font-size: clamp(3rem, 18vw, 6rem);
            }


            .unit-badge {
                padding: 10px 20px;
                border-width: 2px;
                margin-top: -40px;
            }

            .gram-number {
                min-height: 100px;
                width: clamp(280px, 85vw, 400px);
            }

            #weight.five-digits {
                font-size: clamp(2rem, 13vw, 4rem);
            }

            .unit {
                font-size: 1.5rem;
                letter-spacing: 2px;
            }

            .unit::after {
                font-size: 1.5rem;
                letter-spacing: 2px;
            }

            .chest-coins {
                width: 100vw;
                height: auto;
            }
        }

        @media (max-width: 900px) {
            .chest-coins {
                height: 270px;
                content: url('./assets/okada-building-mobile-original.png');
            }

            .gold-text {
                width: 65vw;
                top: 50%;
            }

            .casino-board {
                bottom: 40px;
                padding: 60px 30px;
                border-width: 12px;
                margin: 20px auto;
                width: clamp(280px, 95vw, 350px);
            }
        }

        @media (max-width: 890px) {
            .chest-coins {
                height: 220px;
            }

            .gold-text {
                width: 85%;
                top: 50%;
            }

            .casino-board {
                width: clamp(240px, 85vw, 350px);
                bottom: 55px;
            }

            .unit-badge {
                margin-top: -60px;
            }

            .gram-number {
                width: clamp(250px, 85vw, 320px);
                min-height: 90px;
                padding: 25px 25px;
            }

            #weight {
                font-size: clamp(2.5rem, 15vw, 5rem);
            }

            #weight.five-digits {
                font-size: clamp(2rem, 12vw, 3.5rem);
            }

            .unit {
                font-size: 2.5rem;
                letter-spacing: 2px;
            }

            .unit::after {
                font-size: 2.5rem;
                letter-spacing: 2px;
            }
        }
    </style>
</head>

<body>
    <canvas id="canvas"></canvas>

    <div><img class="top-logo" src="./assets/Okada_Manila_logo.webp" alt=""></div>
    <div class="casino-board">
        <img class="gold-text" src="./assets/gold-for-cash-text.webp" alt="">
    </div>
    <div class="unit-badge">
        <div class="gram-number">
            <span id="weight"></span>
        </div>
        <p class="unit" data-text="Grams">Grams</p>
    </div>
    <div>
        <img class="chest-coins"
            style="filter: drop-shadow(0px 0px 82px #8be0ff); position: absolute; bottom: 0; left: 0;"
            src="./assets/chest-coins.webp" alt="">
    </div>

    <script>
        function updateWeight(val) {
            let newVal = parseFloat(val);
            if (isNaN(newVal)) return;

            let textVal = newVal.toFixed(0);
            let weightEl = document.getElementById("weight");
            weightEl.innerText = textVal;

            if (textVal.length >= 5) {
                weightEl.classList.add("five-digits");
            } else {
                weightEl.classList.remove("five-digits");
            }
        }

        // --- Robust polling for multi-week continuous operation ---
        // Uses setTimeout (sequential) instead of setInterval to prevent
        // request pile-up. Includes cache-busting & request timeouts.

        function pollWeight() {
            const controller = new AbortController();
            const timeout = setTimeout(() => controller.abort(), 3000);

            fetch("weight.txt?_=" + Date.now(), {
                cache: "no-store",
                signal: controller.signal
            })
                .then(r => r.text())
                .then(t => updateWeight(t))
                .catch(() => { })
                .finally(() => {
                    clearTimeout(timeout);
                    setTimeout(pollWeight, 100);
                });
        }
        pollWeight();

        function pollWinner() {
            const controller = new AbortController();
            const timeout = setTimeout(() => controller.abort(), 3000);

            fetch("stats.txt?_=" + Date.now(), {
                cache: "no-store",
                signal: controller.signal
            })
                .then(r => r.text())
                .then(t => {
                    if (t.trim() == "1") {
                        window.location.reload();
                    }
                })
                .catch(() => { })
                .finally(() => {
                    clearTimeout(timeout);
                    setTimeout(pollWinner, 200);
                });
        }
        pollWinner();

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

        const particleImg = new Image();
        particleImg.src = './assets/particles.png';

        function animateDust() {
            requestAnimationFrame(animateDust);
            ctx.clearRect(0, 0, cw, ch);

            ambientDust.forEach(p => {
                p.y += p.vy;
                p.x += p.vx;

                if (p.y < -10) p.y = ch + 10;
                if (p.x < -10) p.x = cw + 10;
                if (p.x > cw + 10) p.x = -10;

                ctx.save();
                ctx.globalAlpha = p.opacity;
                let size = p.r * 2;
                ctx.drawImage(particleImg, p.x - p.r, p.y - p.r, size, size);
                ctx.restore();
            });
        }

        animateDust();
    </script>
</body>

</html>
<!-- new -->