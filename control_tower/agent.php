<?php
// control_tower/agent.php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

// Helper to run command as the logged-in user for GUI actions
// We assume the user is 'patricklmbn' or similar. 
// For X11/Wayland commands, we need to export DISPLAY or use specific tools.
function run_cmd($cmd) {
    return shell_exec($cmd);
}

switch ($action) {
    case 'status':
        $response = [
            'status' => 'online',
            'hostname' => gethostname(),
            'ip' => $_SERVER['SERVER_ADDR'] ?? 'Unknown',
            'uptime' => str_replace('up ', '', trim(shell_exec('uptime -p'))),
            'weight' => trim(@file_get_contents('../weight.txt') ?: '0')
        ];
        break;

    case 'rotate':
        // We use & to run in background so the PHP request doesn't hang
        run_cmd('export DISPLAY=:0 && ../rotate_screen.sh toggle > /dev/null 2>&1 &');
        $response = ['success' => true, 'message' => 'Rotation toggled'];
        break;

    case 'restart_podium':
        // Killing it triggers the loop in start_podium.sh to restart it
        run_cmd('pkill -f podium.py');
        $response = ['success' => true, 'message' => 'Podium script killed (auto-restarting)'];
        break;

    case 'reboot':
        run_cmd('sudo reboot');
        $response = ['success' => true, 'message' => 'System rebooting'];
        break;

    default:
        $response = ['error' => 'Unknown action: ' . $action];
}

echo json_encode($response);
