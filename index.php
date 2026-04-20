<!DOCTYPE html>
<html>
<head>
<style>
body {
  background: black;
  color: white;
  text-align: center;
  font-family: Arial;
}

#title {
  font-size: 60px;
  margin-top: 50px;
}

#weight {
  font-size: 120px;
  margin-top: 80px;
}

.winner {
  background: green;
}
</style>
</head>

<body>

<div id="title">Gold2Cash</div>
<div id="weight">0.00</div>

<script>

function updateWeight(val){
    document.getElementById("weight").innerText = val;
}

function showWinner(){
    document.body.classList.add("winner");
    document.getElementById("title").innerText = "WE HAVE A WINNER";
}

setInterval(()=>{
 fetch("weight.php")
 .then(r=>r.text())
 .then(t=>updateWeight(t))
},200);

setInterval(()=>{
 fetch("winner.php")
 .then(r=>r.text())
 .then(t=>{
   if(t=="1"){
      showWinner();
   } else {
        document.body.classList.remove("winner");
        document.getElementById("title").innerText = "Gold2Cash";
   }
 })
},200);

</script>

</body>
</html>