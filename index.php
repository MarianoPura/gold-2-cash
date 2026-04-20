<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
:root {
  --screen-bg: #050505;
  --text-main: #ffd66b;
  --text-glow: rgba(255, 210, 80, 0.85);
}

* {
  box-sizing: border-box;
}

html,
body {
  width: 100%;
  height: 100%;
  margin: 0;
  overflow: hidden;
}

body {
  background: radial-gradient(circle at 50% 30%, #1c2845 0%, var(--screen-bg) 60%);
  display: flex;
  align-items: center;
  justify-content: center;
  font-family: "Trebuchet MS", "Segoe UI", sans-serif;
  color: white;
}

#stage {
  position: relative;
  width: min(100vw, calc(100vh * (2 / 3)));
  height: min(100vh, calc(100vw * (3 / 2)));
  aspect-ratio: 2 / 3;
  transition: box-shadow 0.35s ease, filter 0.35s ease;
}

.stageLayer {
  position: absolute;
  inset: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
}

#baseImage {
  z-index: 1;
}

#winnerOverlay {
  display: none;
  z-index: 2;
  pointer-events: none;
}

#weight {
  position: absolute;
  left: 50%;
  top: 50%;
  transform: translate(-50%, -50%);
  z-index: 3;
  margin: 0;
  min-width: 52%;
  text-align: center;
  font-size: clamp(70px, 11.8vh, 165px);
  font-weight: 900;
  line-height: 1;
  color: var(--text-main);
  text-shadow:
    0 0 10px rgba(0, 0, 0, 0.92),
    0 0 20px rgba(0, 0, 0, 0.9),
    0 0 34px var(--text-glow);
}

body.winner #stage {
  box-shadow: 0 0 60px rgba(30, 255, 60, 0.8);
  filter: saturate(1.15);
}

@media (max-width: 500px) {
  #weight {
    top: 50%;
    font-size: clamp(62px, 11vh, 132px);
  }
}
</style>
</head>

<body>
<div id="stage">
  <img id="baseImage" class="stageLayer" src="image.png" alt="" aria-hidden="true" decoding="async" loading="eager">
  <img id="winnerOverlay" class="stageLayer" src="winner.png" alt="" aria-hidden="true" decoding="async" loading="eager">
  <div id="weight">0.00</div>
</div>

<script>

function updateWeight(val){
    document.getElementById("weight").innerText = val;
}

function showWinner(){
    document.getElementById("weight").style.display = "none";
    document.body.classList.add("winner");
  document.getElementById("winnerOverlay").style.display = "block";
}

function hideWinner(){
    document.getElementById("weight").style.display = "block";
  document.body.classList.remove("winner");
  document.getElementById("winnerOverlay").style.display = "none";
}

setInterval(()=>{
 fetch("weight.php")
 .then(r=>r.text())
 .then(t=>updateWeight(t.trim()))
},200);

setInterval(()=>{
 fetch("winner.php")
 .then(r=>r.text())
 .then(t=>{
  if(t.trim()=="1"){
      showWinner();
   } else {
     hideWinner();
   }
 })
},200);

</script>

</body>
</html>