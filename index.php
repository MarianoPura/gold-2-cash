<div id="content-area">
  <?php
  $winnerFile = "stats.txt";
  $winner = file_exists($winnerFile) ? trim(file_get_contents($winnerFile)) : "0";

  if ($winner == "1") {
    include 'winner_ui.php';
  } else {
    include 'default.php';
  }
  ?>
</div>

<script>
  let currentStatus = "<?php echo $winner; ?>";

  function pollServer() {
    fetch('stats.txt')
      .then(response => response.text())
      .then(newStatus => {
        if (newStatus !== currentStatus) {
          window.location.reload();
        }
      });
  }

  setInterval(pollServer, 500);
</script>