<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
date_default_timezone_set('Asia/Jakarta');

try {
    $pdo = new PDO(
        'mysql:host=localhost;dbname=yara_laundry;charset=utf8mb4',
        'root',
        '',
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
    $pdo->exec("SET time_zone = '+07:00'");

    // Buat akun default & setting ongkir otomatis jika tabel masih kosong
    if ((int)$pdo->query("SELECT COUNT(*) FROM users")->fetchColumn() === 0) {
        $stmt = $pdo->prepare("INSERT INTO users (code, name, short_name, email, password_hash, role) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute(['KASIR', 'KASIR', 'KASIR/ADMIN', 'kasir@yaralaundry.com', password_hash('kasir123', PASSWORD_BCRYPT), 'kasir']);
        $stmt->execute(['OWNER', 'YARA OWNER', 'OWNER', 'owner@yaralaundry.com', password_hash('owner123', PASSWORD_BCRYPT), 'owner']);
    }
    $pdo->exec("INSERT IGNORE INTO settings (setting_key, setting_value) VALUES ('delivery_fee', '5000')");
} catch (PDOException $e) {
    jsonOut(['status' => 'OFFLINE', 'error' => 'Koneksi MySQL gagal: ' . $e->getMessage()], 500);
}

function jsonOut($data, $code = 200)
{
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function jsonIn()
{
    $data = json_decode(file_get_contents('php://input'), true);
    return is_array($data) ? $data : $_POST;
}

function requireAuth($ownerOnly = false)
{
    if (!isset($_SESSION['user'])) {
        jsonOut(['error' => 'Sesi berakhir. Silakan login kembali.'], 401);
    }
    if ($ownerOnly && $_SESSION['user']['role'] !== 'owner') {
        jsonOut(['error' => 'Akses ditolak! Fitur ini khusus OWNER.'], 403);
    }
    return $_SESSION['user'];
}