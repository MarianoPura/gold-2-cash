<?php
// control_tower/index.php
// Detect local IP to guess the subnet
$local_ip = explode('.', $_SERVER['SERVER_ADDR'] ?? '192.168.1.1');
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
