// URL API untuk mengontrol relay (gunakan path relatif)
const API_BASE_URL = window.location.origin;

// Variabel state
let relayState = false;
let espConnected = false;

document.addEventListener('DOMContentLoaded', function() {
    const relaySwitch = document.getElementById('relaySwitch');
    
    if (relaySwitch) {
        relaySwitch.addEventListener('change', toggleRelay);
        checkESP32Connection();
    }
});

// Fungsi toggle relay
async function toggleRelay() {
    const isChecked = document.getElementById('relaySwitch').checked;
    updateRelayStatus(isChecked);
    
    try {
        // Kirim perintah ke ESP32
        const response = await fetch(`${API_BASE_URL}/api/relay`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                state: isChecked ? 'on' : 'off'
            })
        });
        
        if (!response.ok) {
            throw new Error('Gagal mengontrol relay');
        }
        
        const data = await response.json();
        console.log('Respon dari server:', data);
        
        // Update status berdasarkan respons
        if (data.success) {
            relayState = data.relayState;
            document.getElementById('relaySwitch').checked = relayState;
            updateRelayStatus(relayState);
        }
        
    } catch (error) {
        console.error('Error:', error);
        alert('Gagal terhubung ke server. Pastikan koneksi internet stabil.');
        document.getElementById('relaySwitch').checked = !isChecked;
        updateRelayStatus(!isChecked);
    }
}

// Update status relay
function updateRelayStatus(isOn) {
    const relayStatus = document.getElementById('relayStatus');
    if (isOn) {
        relayStatus.textContent = 'HIDUP';
        relayStatus.className = 'status on';
    } else {
        relayStatus.textContent = 'MATI';
        relayStatus.className = 'status off';
    }
}

// Cek koneksi ESP32
async function checkESP32Connection() {
    try {
        const response = await fetch(`${API_BASE_URL}/api/status`);
        
        if (response.ok) {
            const data = await response.json();
            
            if (data.success) {
                espConnected = true;
                relayState = data.relayState;
                
                const connectionStatus = document.getElementById('connectionStatus');
                connectionStatus.innerHTML = '<i class="fas fa-check-circle"></i> Terhubung ke ESP32';
                connectionStatus.className = 'connection-status connected';
                
                // Update status relay
                document.getElementById('relaySwitch').checked = relayState;
                updateRelayStatus(relayState);
            } else {
                throw new Error('Status tidak valid');
            }
        } else {
            throw new Error('ESP32 tidak merespon');
        }
    } catch (error) {
        console.error('Error:', error);
        espConnected = false;
        const connectionStatus = document.getElementById('connectionStatus');
        connectionStatus.innerHTML = '<i class="fas fa-times-circle"></i> Tidak terhubung ke ESP32';
        connectionStatus.className = 'connection-status disconnected';
    }
    
    // Cek ulang status setiap 5 detik
    setTimeout(checkESP32Connection, 5000);
}