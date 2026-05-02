// control_tower/script.js

const PC_GRID = document.getElementById('pc-grid');
const SCAN_STATUS = document.getElementById('scan-status');

// Configuration
const SUB_NET = '192.168.1'; // We'll try to detect this or let the user config it
const IP_RANGE = [1, 254]; 

let foundDevices = new Set();

async function checkDevice(ip) {
    try {
        const controller = new AbortController();
        const timeoutId = setTimeout(() => controller.abort(), 2000); // 2s timeout per check

        const response = await fetch(`http://${ip}/gold-2-cash/control_tower/agent.php?action=status`, {
            signal: controller.signal
        });

        if (response.ok) {
            const data = await response.json();
            updateDeviceCard(ip, data);
            return true;
        }
    } catch (e) {
        // Device not found or agent not running
    }
    return false;
}

function updateDeviceCard(ip, data) {
    let card = document.getElementById(`pc-${ip.replace(/\./g, '-')}`);
    
    if (!card) {
        card = document.createElement('div');
        card.id = `pc-${ip.replace(/\./g, '-')}`;
        card.className = 'pc-card';
        PC_GRID.appendChild(card);
    }

    card.innerHTML = `
        <h2>${data.hostname}</h2>
        <span class="pc-ip">${ip}</span>
        
        <div class="pc-stats">
            <div class="stat-row"><span>Uptime</span> <span>${data.uptime}</span></div>
            <div class="stat-row"><span>Current Weight</span> <span>${data.weight}kg</span></div>
            <div class="stat-row"><span>Status</span> <span>ONLINE</span></div>
        </div>

        <div class="controls">
            <button onclick="sendAction('${ip}', 'rotate')">Rotate Screen</button>
            <button onclick="sendAction('${ip}', 'restart_podium')">Restart Podium</button>
            <button onclick="sendAction('${ip}', 'reboot')" class="danger">Reboot System</button>
            <button onclick="checkDevice('${ip}')">Refresh</button>
        </div>
    `;
}

async function sendAction(ip, action) {
    if (action === 'reboot' && !confirm(`Are you sure you want to reboot ${ip}?`)) return;
    
    try {
        const response = await fetch(`http://${ip}/gold-2-cash/control_tower/agent.php?action=${action}`);
        const result = await response.json();
        alert(result.message || result.error);
        
        // Refresh status after action
        setTimeout(() => checkDevice(ip), 1000);
    } catch (e) {
        alert('Action failed: Could not connect to agent.');
    }
}

async function scanNetwork() {
    SCAN_STATUS.innerText = 'Scanning Network...';
    
    // We scan in chunks to avoid overwhelming the browser
    const chunkSize = 20;
    for (let i = IP_RANGE[0]; i <= IP_RANGE[1]; i += chunkSize) {
        const promises = [];
        for (let j = i; j < i + chunkSize && j <= IP_RANGE[1]; j++) {
            promises.push(checkDevice(`${SUB_NET}.${j}`));
        }
        await Promise.all(promises);
    }
    
    SCAN_STATUS.innerText = 'Scan Complete';
}

// Initial Scan
scanNetwork();

// Auto-refresh every 30 seconds
setInterval(scanNetwork, 30000);
