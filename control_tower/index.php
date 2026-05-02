<?php
// control_tower/index.php

// Attempt to get a real network IP, not just 127.0.0.1
$local_ip_raw = $_SERVER['SERVER_ADDR'] ?? '';
if (!$local_ip_raw || strpos($local_ip_raw, '127.0.') === 0 || $local_ip_raw === '::1') {
    // If we are on localhost, try to find the real IP via shell
    $local_ip_raw = exec("hostname -I | awk '{print $1}'") ?: '192.168.1.1';
}

$local_ip = explode('.', $local_ip_raw);
$subnet = $local_ip[0] . '.' . $local_ip[1] . '.' . $local_ip[2];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CONTROL TOWER</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="loading-overlay" id="scan-status">Initializing...</div>
    
    <header>
        <div>
            <div class="status-bar">Central Management System</div>
            <h1>Control Tower</h1>
        </div>
        <div style="text-align: right">
            <button onclick="scanNetwork()">Force Scan</button>
        </div>
    </header>

    <main id="pc-grid">
        <!-- Devices will be injected here -->
    </main>

    <script>
        // Inject the detected subnet from PHP
        const SUB_NET = '<?php echo $subnet; ?>';
    </script>
    <script src="script.js"></script>
</body>
</html>
