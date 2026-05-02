// control_tower/script.js

const PC_GRID = document.getElementById('pc-grid');
const SCAN_STATUS = document.getElementById('scan-status');

// Configuration - We scan multiple common subnets
const SCAN_RANGES = [
    SUB_NET,        // Detected subnet (e.g. 192.168.1)
    '10.42.0',      // Default Linux Hotspot subnet
    '192.168.4'     // Common ESP32/IoT subnet
];
const IP_START = 1;
const IP_END = 254; 

let foundDevices = new Set();

async function checkDevice(ip) {
    if (foundDevices.has(ip)) return; // Don't re-scan if already found

    try {
        const controller = new AbortController();
        const timeoutId = setTimeout(() => controller.abort(), 1500); // 1.5s timeout

        const response = await fetch(`http://${ip}/gold-2-cash/control_tower/agent.php?action=status`, {
            signal: controller.signal
        });

        if (response.ok) {
            const data = await response.json();
            foundDevices.add(ip);
            updateDeviceCard(ip, data);
            return true;
        }
    } catch (e) {
        // Device not found
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
            <div class="stat-row"><span>Weight</span> <span>${data.weight}kg</span></div>
            <div class="stat-row"><span>Status</span> <span>ONLINE</span></div>
        </div>

        <div class="controls">
            <button onclick="sendAction('${ip}', 'rotate')">Rotate Screen</button>
            <button onclick="sendAction('${ip}', 'restart_podium')">Restart Podium</button>
            <button onclick="sendAction('${ip}', 'reboot')" class="danger">Reboot</button>
            <button onclick="checkDevice('${ip}')">Refresh</button>
        </div>
    `;
}

async function sendAction(ip, action) {
    if (action === 'reboot' && !confirm(`Are you sure?`)) return;
    
    try {
        const response = await fetch(`http://${ip}/gold-2-cash/control_tower/agent.php?action=${action}`);
        const result = await response.json();
        alert(result.message || result.error);
        setTimeout(() => checkDevice(ip), 2000);
    } catch (e) {
        alert('Action failed.');
    }
}

async function scanNetwork() {
    SCAN_STATUS.innerText = 'Scanning Network...';
    
    for (const range of SCAN_RANGES) {
        if (!range || range.startsWith('127.0')) continue;
        
        const chunkSize = 30;
        for (let i = IP_START; i <= IP_END; i += chunkSize) {
            const promises = [];
            for (let j = i; j < i + chunkSize && j <= IP_END; j++) {
                promises.push(checkDevice(`${range}.${j}`));
            }
            await Promise.all(promises);
        }
    }
    
    SCAN_STATUS.innerText = 'Scan Complete';
}

// Initial Scan
scanNetwork();
setInterval(scanNetwork, 60000);
