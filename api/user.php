<?php
require_once __DIR__ . '/../config/database.php';

$route  = $_GET['route'] ?? '';
$action = $_GET['action'] ?? '';
$method = $_SERVER['REQUEST_METHOD'];

function normalizePhone($raw)
{
    $digits = preg_replace('/\D+/', '', (string)$raw);
    if (str_starts_with($digits, '0')) {
        $digits = '62' . substr($digits, 1);
    } elseif (!str_starts_with($digits, '62')) {
        $digits = '62' . $digits;
    }
    $local = substr($digits, 2);
    if (strlen($local) >= 9) {
        return '+62 ' . substr($local, 0, 3) . '-' . substr($local, 3, 4) . '-' . substr($local, 7);
    }
    return '+62 ' . $local;
}

function requireCustomer()
{
    if (!isset($_SESSION['customer'])) {
        jsonOut(['error' => 'Sesi pelanggan berakhir. Silakan login kembali.'], 401);
    }
    return $_SESSION['customer'];
}

try {
    // =========================================================
    // 1. AUTENTIKASI PELANGGAN
    // =========================================================
    if ($route === 'auth') {
        if ($action === 'me') {
            jsonOut(['authenticated' => true, 'customer' => requireCustomer()]);
        }

        if ($action === 'logout') {
            unset($_SESSION['customer']);
            if ($method === 'GET') {
                header('Location: ../user/login.php');
                exit;
            }
            jsonOut(['success' => true]);
        }

        if ($method === 'POST' and $action === 'login') {
            $in    = jsonIn();
            $phone = normalizePhone($in['phone'] ?? '');
            $pass  = $in['password'] ?? '';

            $stmt = $pdo->prepare("SELECT * FROM customers WHERE phone = ? LIMIT 1");
            $stmt->execute([$phone]);
            $cust = $stmt->fetch();

            if (!$cust or empty($cust['password_hash']) or !password_verify($pass, $cust['password_hash'])) {
                jsonOut(['error' => 'Nomor telepon atau kata sandi salah.'], 401);
            }

            session_regenerate_id(true);
            $_SESSION['customer'] = [
                'id'      => (int)$cust['id'],
                'name'    => $cust['name'],
                'phone'   => $cust['phone'],
                'address' => $cust['address'] ?? ''
            ];
            jsonOut(['success' => true, 'customer' => $_SESSION['customer']]);
        }

        if ($method === 'POST' and $action === 'request_otp') {
            $in      = jsonIn();
            $phone   = normalizePhone($in['phone'] ?? '');
            $purpose = ($in['purpose'] ?? 'REGISTRASI') === 'RESET_PASSWORD' ? 'RESET_PASSWORD' : 'REGISTRASI';

            if (strlen(preg_replace('/\D+/', '', $phone)) < 10) {
                jsonOut(['error' => 'Nomor WhatsApp tidak valid.'], 400);
            }

            $stmt = $pdo->prepare("SELECT id, password_hash FROM customers WHERE phone = ? LIMIT 1");
            $stmt->execute([$phone]);
            $existing = $stmt->fetch();

            if ($purpose === 'REGISTRASI' and $existing and !empty($existing['password_hash'])) {
                jsonOut(['error' => 'Nomor WhatsApp sudah terdaftar. Silakan login.'], 400);
            }
            if ($purpose === 'RESET_PASSWORD' and (!$existing or empty($existing['password_hash']))) {
                jsonOut(['error' => 'Nomor WhatsApp belum terdaftar sebagai akun aktif.'], 404);
            }

            $otpCode = (string)random_int(100000, 999999);
            $expires = date('Y-m-d H:i:s', strtotime('+5 minutes'));

            $pdo->prepare("INSERT INTO otp_codes (phone, purpose, code_hash, expires_at) VALUES (?, ?, ?, ?)")
                ->execute([$phone, $purpose, password_hash($otpCode, PASSWORD_BCRYPT), $expires]);

            $_SESSION['otp_pending'] = [
                'phone'   => $phone,
                'purpose' => $purpose,
                'name'    => trim($in['name'] ?? ''),
                'pass'    => $in['password'] ?? ''
            ];

            jsonOut([
                'success'  => true,
                'phone'    => $phone,
                'demo_otp' => $otpCode,
                'message'  => 'Kode OTP terkirim ke ' . $phone
            ]);
        }

        if ($method === 'POST' and $action === 'verify_otp') {
            $in      = jsonIn();
            $phone   = normalizePhone($in['phone'] ?? ($_SESSION['otp_pending']['phone'] ?? ''));
            $purpose = ($in['purpose'] ?? ($_SESSION['otp_pending']['purpose'] ?? 'REGISTRASI')) === 'RESET_PASSWORD' ? 'RESET_PASSWORD' : 'REGISTRASI';
            $code    = trim($in['otp'] ?? '');

            $stmt = $pdo->prepare("SELECT * FROM otp_codes WHERE phone = ? AND purpose = ? AND is_used = 0 ORDER BY id DESC LIMIT 1");
            $stmt->execute([$phone, $purpose]);
            $otpRow = $stmt->fetch();

            if (!$otpRow or strtotime($otpRow['expires_at']) < time()) {
                jsonOut(['error' => 'Kode OTP tidak valid atau sudah kedaluwarsa.'], 400);
            }
            if ((int)$otpRow['attempts'] >= 5) {
                jsonOut(['error' => 'Terlalu banyak percobaan salah. Minta kode OTP baru.'], 429);
            }
            if (!password_verify($code, $otpRow['code_hash'])) {
                $pdo->prepare("UPDATE otp_codes SET attempts = attempts + 1 WHERE id = ?")->execute([$otpRow['id']]);
                jsonOut(['error' => 'Kode OTP salah.'], 400);
            }

            $pdo->prepare("UPDATE otp_codes SET is_used = 1 WHERE id = ?")->execute([$otpRow['id']]);

            if ($purpose === 'REGISTRASI') {
                $name = trim($in['name'] ?? ($_SESSION['otp_pending']['name'] ?? 'Pelanggan'));
                $pass = $in['password'] ?? ($_SESSION['otp_pending']['pass'] ?? '');
                if (strlen($pass) < 6) {
                    jsonOut(['error' => 'Kata sandi minimal 6 karakter.'], 400);
                }
                $hash = password_hash($pass, PASSWORD_BCRYPT);

                $cek = $pdo->prepare("SELECT id, address FROM customers WHERE phone = ?");
                $cek->execute([$phone]);
                if ($row = $cek->fetch()) {
                    $pdo->prepare("UPDATE customers SET name = ?, password_hash = ? WHERE id = ?")
                        ->execute([$name, $hash, $row['id']]);
                    $custId = (int)$row['id'];
                    $addr   = $row['address'] ?? '';
                } else {
                    $pdo->prepare("INSERT INTO customers (name, phone, password_hash) VALUES (?, ?, ?)")
                        ->execute([$name, $phone, $hash]);
                    $custId = (int)$pdo->lastInsertId();
                    $addr   = '';
                }

                unset($_SESSION['otp_pending']);
                $_SESSION['customer'] = ['id' => $custId, 'name' => $name, 'phone' => $phone, 'address' => $addr];
                jsonOut(['success' => true, 'customer' => $_SESSION['customer']]);
            }

            if ($purpose === 'RESET_PASSWORD') {
                $newPass = $in['new_password'] ?? '';
                if (strlen($newPass) < 6) {
                    jsonOut(['error' => 'Kata sandi baru minimal 6 karakter.'], 400);
                }
                $pdo->prepare("UPDATE customers SET password_hash = ? WHERE phone = ?")
                    ->execute([password_hash($newPass, PASSWORD_BCRYPT), $phone]);
                unset($_SESSION['otp_pending']);
                jsonOut(['success' => true, 'message' => 'Kata sandi berhasil diperbarui.']);
            }
        }
    }

    // =========================================================
    // 2. PROFIL PELANGGAN
    // =========================================================
    if ($route === 'profile') {
        $cust = requireCustomer();
        $in   = jsonIn();

        if ($method === 'PUT' and $action === 'update') {
            $name    = trim($in['name'] ?? $cust['name']);
            $phone   = normalizePhone($in['phone'] ?? $cust['phone']);
            $address = array_key_exists('address', $in) ? trim((string)$in['address']) : $cust['address'];

            $pdo->prepare("UPDATE customers SET name = ?, phone = ?, address = ? WHERE id = ?")
                ->execute([$name, $phone, $address, $cust['id']]);

            $_SESSION['customer']['name']    = $name;
            $_SESSION['customer']['phone']   = $phone;
            $_SESSION['customer']['address'] = $address;
            jsonOut(['success' => true, 'customer' => $_SESSION['customer']]);
        }

        if ($method === 'PUT' and $action === 'change_password') {
            $newPass = $in['new_password'] ?? '';
            if (strlen($newPass) < 6) {
                jsonOut(['error' => 'Kata sandi baru minimal 6 karakter.'], 400);
            }
            $pdo->prepare("UPDATE customers SET password_hash = ? WHERE id = ?")
                ->execute([password_hash($newPass, PASSWORD_BCRYPT), $cust['id']]);
            jsonOut(['success' => true]);
        }
    }

    // =========================================================
    // 3. PESANAN PELANGGAN (SESUAI SKEMA yara_laundry_3.sql)
    // =========================================================
    if ($route === 'orders') {
        $cust = requireCustomer();

        if ($method === 'POST' and $action === 'create') {
            $in        = jsonIn();
            $serviceId = (int)($in['service_id'] ?? 0);
            $weight    = (float)($in['weight_qty'] ?? 0);
            $delivery  = ($in['delivery_type'] ?? 'DROP_OFF') === 'ANTAR_JEMPUT' ? 'ANTAR_JEMPUT' : 'DROP_OFF';
            $address   = trim((string)($in['pickup_address'] ?? ''));
            $note      = trim((string)($in['notes'] ?? ''));

            if ($serviceId <= 0 or $weight <= 0) {
                jsonOut(['error' => 'Layanan dan berat/jumlah cucian wajib diisi.'], 400);
            }

            $stmt = $pdo->prepare("SELECT * FROM services WHERE id = ? LIMIT 1");
            $stmt->execute([$serviceId]);
            $srv = $stmt->fetch();
            if (!$srv) {
                jsonOut(['error' => 'Layanan tidak ditemukan.'], 404);
            }

            $feeStmt = $pdo->query("SELECT setting_value FROM settings WHERE setting_key = 'delivery_fee' LIMIT 1");
            $delivFee = $delivery === 'ANTAR_JEMPUT' ? (int)($feeStmt->fetchColumn() ?: 5000) : 0;

            $subtotal   = (int)round($weight * (int)$srv['price_per_unit']);
            $totalPrice = $subtotal + $delivFee;

            $statusStep = $delivery === 'ANTAR_JEMPUT' ? 0 : 1;
            $statusNote = $delivery === 'ANTAR_JEMPUT' ? 'Menunggu Kurir Jemput' : 'Menunggu Timbang Ulang';
            $timeLabel  = 'Hari ini, ' . date('H:i');

            $pdo->beginTransaction();

            $maxNum = (int)$pdo->query("SELECT COALESCE(MAX(CAST(SUBSTRING(receipt_no, 6) AS UNSIGNED)), 8925) FROM orders")->fetchColumn();
            $receiptNo = '#YYL-' . ($maxNum + 1);

            $ins = $pdo->prepare("INSERT INTO orders
                (receipt_no, customer_id, service_id, delivery_type, pickup_address, delivery_fee, weight, is_estimated_weight, status_step, status_note, payment_status, total_price, notes, time_label)
                VALUES (?, ?, ?, ?, ?, ?, ?, 1, ?, ?, 'BELUM', ?, ?, ?)");
            $ins->execute([
                $receiptNo,
                $cust['id'],
                $srv['id'],
                $delivery,
                $delivery === 'ANTAR_JEMPUT' ? $address : null,
                $delivFee,
                $weight,
                $statusStep,
                $statusNote,
                $totalPrice,
                $note,
                $timeLabel
            ]);
            $orderId = (int)$pdo->lastInsertId();

            $logText = "Pesanan " . ($delivery === 'ANTAR_JEMPUT' ? 'Antar-Jemput' : 'Drop-Off') . " {$receiptNo} masuk dari aplikasi pelanggan (Estimasi {$weight} {$srv['unit']})";
            $pdo->prepare("INSERT INTO order_logs (order_id, action, actor) VALUES (?, ?, ?)")
                ->execute([$orderId, $logText, 'PELANGGAN (' . $cust['name'] . ')']);

            if ($delivery === 'ANTAR_JEMPUT' and $address !== '') {
                $pdo->prepare("UPDATE customers SET address = ? WHERE id = ?")->execute([$address, $cust['id']]);
                $_SESSION['customer']['address'] = $address;
            }

            $pdo->commit();

            jsonOut([
                'success' => true,
                'order'   => [
                    'id'           => $orderId,
                    'order_code'   => $receiptNo,
                    'service_name' => $srv['name'],
                    'unit_type'    => $srv['unit'],
                    'weight_qty'   => $weight,
                    'total_amount' => $totalPrice
                ]
            ]);
        }
    }

    jsonOut(['error' => 'Endpoint user tidak ditemukan.'], 404);
} catch (PDOException $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    $msg = $e->getCode() === '23000' ? 'Data bentrok atau nomor telepon sudah terdaftar.' : $e->getMessage();
    jsonOut(['error' => $msg], 400);
}