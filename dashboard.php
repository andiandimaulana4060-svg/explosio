<?php
session_start();

// Redirect jika belum login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: index.php');
    exit;
}

// Proses logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explosio - Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">
                <i class="fas fa-bolt"></i>
            </div>
            <h1>EXPLOSIO</h1>
            <p>Kontrol Relay ESP32</p>
        </div>
        
        <div class="dashboard">
            <h2>Kontrol Relay</h2>
            <p>Status Relay: <span id="relayStatus" class="status off">MATI</span></p>
            
            <label class="switch">
                <input type="checkbox" id="relaySwitch">
                <span class="slider"></span>
            </label>
            <p>Switch ON/OFF Relay</p>
            
            <div id="connectionStatus" class="connection-status disconnected">
                <i class="fas fa-times-circle"></i> Tidak terhubung ke ESP32
            </div>
            
            <a href="dashboard.php?logout=true" class="btn logout-btn">Logout</a>
        </div>
        
        <div class="footer">
            &copy; 2023 Explosio - IoT Control System | Powered by ESP32 & Ngrok
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>