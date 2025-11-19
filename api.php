<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Simpan status relay dalam session
session_start();

// Inisialisasi state relay jika belum ada
if (!isset($_SESSION['relayState'])) {
    $_SESSION['relayState'] = false;
}

// Dapatkan path request
$request_uri = strtok($_SERVER['REQUEST_URI'], '?');
$path = parse_url($request_uri, PHP_URL_PATH);

// Endpoint untuk mendapatkan status
if ($_SERVER['REQUEST_METHOD'] === 'GET' && $path === '/api/status') {
    echo json_encode([
        'success' => true,
        'relayState' => $_SESSION['relayState'],
        'connected' => true,
        'message' => 'Status relay berhasil diambil'
    ]);
    exit;
}

// Endpoint untuk mengontrol relay
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $path === '/api/relay') {
    $input = json_decode(file_get_contents('php://input'), true);
    $state = $input['state'] ?? '';
    
    if ($state === 'on') {
        $_SESSION['relayState'] = true;
        echo json_encode([
            'success' => true,
            'message' => 'Relay dihidupkan',
            'relayState' => true
        ]);
    } elseif ($state === 'off') {
        $_SESSION['relayState'] = false;
        echo json_encode([
            'success' => true,
            'message' => 'Relay dimatikan',
            'relayState' => false
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Perintah tidak valid'
        ]);
    }
    exit;
}

// Default response untuk endpoint tidak dikenali
http_response_code(404);
echo json_encode([
    'success' => false,
    'message' => 'Endpoint tidak ditemukan: ' . $path
]);