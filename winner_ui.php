<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gold 2 Cash - WINNER!</title>
    <style>
        @import url('fonts/fonts.css');

        body {
            background-color: #0b0800;
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
            animation: bgFlash 1.5s infinite alternate;
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

        .casino-board {
            border: 8px solid #ffd700;
            border-radius: 20px;
            padding: 50px 80px;
            background: linear-gradient(135deg, #1f1a00, #0a0800);
            position: relative;
            z-index: 10;
            animation: boardFlash 1s infinite alternate;
        }

        #title {
            font-size: 5rem;
            margin-bottom: 40px;
            margin-top: 0;
            text-transform: uppercase;
            background: linear-gradient(to bottom, #fff7a1, #ffd700, #b8860b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: 4px;
            animation: titleJackpot 0.8s infinite alternate;
        }

        #weight-container {
            background: #000;
            border: 4px solid #b8860b;
            border-radius: 10px;
            padding: 30px 60px;
            display: inline-block;
            position: relative;
            border-color: #ffe866;
            animation: slotWin 0.4s infinite;
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

        @keyframes bgFlash {
            0% { background-image: radial-gradient(circle at center, #2e2200 0%, #000 100%); }
            100% { background-image: radial-gradient(circle at center, #5c4400 0%, #1a1300 100%); }
        }

        @keyframes boardFlash {
            0% { box-shadow: 0 0 40px #b8860b, inset 0 0 30px #b8860b; border-color: #b8860b; }
            50% { box-shadow: 0 0 80px #fff7a1, inset 0 0 50px #fff7a1; border-color: #fff7a1; }
            100% { box-shadow: 0 0 60px #ffd700, inset 0 0 40px #ffd700; border-color: #ffd700; }
        }
        
        @keyframes titleJackpot {
            0% { 
                text-shadow: 0 0 20px #ffd700; 
                transform: scale(1);
            }
            100% { 
                text-shadow: 0 0 40px #fff, 0 0 80px #ffd700, 0 0 120px #ffae00; 
                transform: scale(1.08);
            }
        }

        @keyframes slotWin {
            0% { transform: translateY(0); box-shadow: inset 0 0 30px rgba(255, 215, 0, 0.4); }
            50% { transform: translateY(-4px); box-shadow: inset 0 0 60px rgba(255, 215, 0, 0.9); }
            100% { transform: translateY(0); box-shadow: inset 0 0 30px rgba(255, 215, 0, 0.4); }
        }

        /* Portrait Screen Adjustments */
        @media screen and (orientation: portrait), screen and (max-width: 600px) {
            .casino-board {
                padding: 40px 20px;
                border-width: 5px;
                width: 90vw;
                box-sizing: border-box;
            }
            #title {
                font-size: clamp(2.5rem, 10vw, 5rem);
                margin-bottom: 30px;
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
        }
    </style>
</head>

<body>
    <canvas id="canvas"></canvas>

    <div class="casino-board">
        <h1 id="title">WE HAVE JACKPOT WINNER!</h1>
        <div id="weight-container">
            <span id="weight">0.00</span><span class="unit">g</span>
        </div>
    </div>

<script>
function updateWeight(val){
    let newVal = parseFloat(val);
    if(isNaN(newVal)) return;
    
    if(targetWeight !== newVal) {
        targetWeight = newVal;
        currentWeight = 0; // Always start counting from 0
        if(!isAnimatingWeight) {
            animateWeightDisplay();
        }
    }
}

let currentWeight = 0;
let targetWeight = 0;
let isAnimatingWeight = false;

function animateWeightDisplay(){
    let diff = targetWeight - currentWeight;
    
    if(Math.abs(diff) < 0.01) {
        currentWeight = targetWeight;
        document.getElementById("weight").innerText = currentWeight.toFixed(2);
        isAnimatingWeight = false;
        return;
    }
    
    isAnimatingWeight = true;
    currentWeight += diff * 0.03; // approach target slower (3% each frame)
    document.getElementById("weight").innerText = currentWeight.toFixed(2);
    
    requestAnimationFrame(animateWeightDisplay);
}

// Fetch weight periodically
setInterval(()=>{
 fetch("weight.php")
 .then(r=>r.text())
 .then(t=>updateWeight(t))
 .catch(e=>console.log(e));
},200);

// Pool winner status and jump to default.php when stats.txt == 0
setInterval(()=>{
 fetch("winner.php")
 .then(r=>r.text())
 .then(t=>{
   if(t.trim() != "1"){
      window.location.reload(); // Reload will let index.php route back to default.php
   }
 })
 .catch(e=>console.log(e));
},200);

/* --- Fireworks System --- */
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

let particles = [];
class Particle {
    constructor(x, y, isTrail = false) {
        this.x = x;
        this.y = y;
        this.isTrail = isTrail;
        const angle = random(0, Math.PI * 2);
        this.color = `hsl(${random(35, 55)}, 100%, ${random(40, 80)}%)`;
        
        if (isTrail) {
            this.vx = 0;
            this.vy = random(-10, -5);
            this.life = random(20, 50);
            this.size = random(2, 4);
            this.color = `hsl(${random(35, 55)}, 100%, 70%)`;
        } else {
            const speed = random(2, 12);
            this.vx = Math.cos(angle) * speed;
            this.vy = Math.sin(angle) * speed;
            this.life = random(40, 80);
            this.size = random(2, 5);
        }
        this.maxLife = this.life;
    }
    
    update() {
        this.x += this.vx;
        this.y += this.vy;
        if(!this.isTrail) {
            this.vy += 0.15; 
            this.vx *= 0.98; 
            this.vy *= 0.98;
        }
        this.life--;
    }
    
    draw(ctx) {
        ctx.globalAlpha = Math.max(0, this.life / this.maxLife);
        ctx.fillStyle = this.color;
        ctx.beginPath();
        ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
        ctx.fill();
        ctx.globalAlpha = 1;
    }
}

function launchFirework() {
    let x = random(100, cw - 100);
    let y = random(100, ch / 2);
    ctx.fillStyle = "rgba(255, 215, 0, 0.1)";
    ctx.fillRect(0, 0, cw, ch);
    for(let i = 0; i < 80; i++) {
        particles.push(new Particle(x, y));
    }
}

function animateFireworks() {
    requestAnimationFrame(animateFireworks);
    ctx.globalCompositeOperation = 'destination-out';
    ctx.fillStyle = 'rgba(0, 0, 0, 0.2)';
    ctx.fillRect(0, 0, cw, ch);
    ctx.globalCompositeOperation = 'lighter';
    
    if(Math.random() < 0.08) {
        launchFirework();
    }
    
    particles = particles.filter(p => p.life > 0);
    particles.forEach(p => {
        p.update();
        p.draw(ctx);
    });
}
animateFireworks();
</script>
</body>
</html>
