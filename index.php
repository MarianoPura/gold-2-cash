<?php
$winnerFile = "stats.txt";
$winner = "0";

if (file_exists($winnerFile)) {
    $winner = trim(file_get_contents($winnerFile));
}

if ($winner == "1") {
    require 'winner_ui.php';
} else {
    require 'default.php';
}
?>