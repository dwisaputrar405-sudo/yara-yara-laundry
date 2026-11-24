<?php
require_once __DIR__ . '/../config/database.php';

$route  = $_GET['route'] ?? 'orders';
$action = $_GET['action'] ?? '';
$method = $_SERVER['REQUEST_METHOD'];
$id     = (int)($_GET['id'] ?? 0);

function getDeliveryFee(PDO $pdo)
{
    return (int)($pdo->query("SELECT setting_value FROM settings WHERE setting_key = 'delivery_fee'")->fetchColumn() ?: 5000);
}

function upsertCustomer(PDO $pdo, $name, $phone, $address = null)
{
    $name = trim($name);
    $phone = trim($phone);
    $addr = ($address !== null && trim($address) !== '') ? trim($address) : null;

    $stmt = $pdo->prepare("SELECT id, address FROM customers WHERE phone = ?");
    $stmt->execute([$phone]);
    if ($row = $stmt->fetch()) {
        $pdo->prepare("UPDATE customers SET name = ?, address = ? WHERE id = ?")
            ->execute([$name, $addr ?? $row['address'], $row['id']]);
        return (int)$row['id'];
    }
    $pdo->prepare("INSERT INTO customers (name, phone, address) VALUES (?, ?, ?)")
        ->execute([$name, $phone, $addr]);
    return (int)$pdo->lastInsertId();
}

function nextReceiptNo(PDO $pdo)
{
    $max = 8925;
    foreach ($pdo->query("SELECT receipt_no FROM orders") as $r) {
        if (preg_match('/(\d+)/', $r['receipt_no'], $m) && (int)$m[1] > $max) {
            $max = (int)$m[1];
        }
    }
    return '#YYL-' . ($max + 1);
}

try {
    // =========================================================
    // 1. ROUTE: AUTH (LOGIN, LOGOUT, VERIFIKASI AKUN)
    // =========================================================
    if ($route === 'auth') {
        if ($action === 'me') {
            jsonOut(['authenticated' => true, 'user' => requireAuth()]);
        }
        if ($action === 'logout') {
            $_SESSION = [];
            session_destroy();
            if ($method === 'GET') {
                header('Location: ../login-admin.php');
                exit;
            }
            jsonOut(['success' => true]);
        }
        if ($method === 'POST' && $action === 'login') {
            $in = jsonIn();
            $email = strtolower(trim($in['email'] ?? ''));
            $pass  = $in['password'] ?? '';

            $stmt = $pdo->prepare("SELECT * FROM users WHERE LOWER(email) = ? LIMIT 1");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if (!$user || !password_verify($pass, $user['password_hash'])) {
                jsonOut(['error' => 'Email atau kata sandi salah.'], 401);
            }

            session_regenerate_id(true);
            $_SESSION['user'] = [
                'id'         => (int)$user['id'],
                'code'       => $user['code'],
                'name'       => $user['name'],
                'short_name' => $user['short_name'],
                'email'      => $user['email'],
                'role'       => $user['role'],
                'login_time' => date('d M Y, H:i:s') . ' WIB'
            ];
            jsonOut(['success' => true, 'user' => $_SESSION['user']]);
        }
        jsonOut(['error' => 'Aksi auth tidak valid.'], 400);
    }

    // =========================================================
    // 2. ROUTE: SERVICES (LAYANAN, HARGA & ONGKIR)
    // =========================================================
    if ($route === 'services') {
        requireAuth();
        if ($method === 'GET') {
            jsonOut([
                'services'     => $pdo->query("SELECT * FROM services ORDER BY id ASC")->fetchAll(),
                'delivery_fee' => getDeliveryFee($pdo)
            ]);
        }

        requireAuth(true); // Hanya Owner untuk POST/PUT/DELETE
        $in = jsonIn();

        if ($method === 'PUT' && $action === 'delivery_fee') {
            $fee = max(0, (int)($in['delivery_fee'] ?? 0));
            $pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES ('delivery_fee', ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)")
                ->execute([(string)$fee]);
            jsonOut(['updated' => true, 'delivery_fee' => $fee]);
        }

        if ($method === 'DELETE' && $id > 0) {
            $pdo->prepare("DELETE FROM services WHERE id = ?")->execute([$id]);
            jsonOut(['deleted' => true]);
        }

        $code  = strtoupper(trim($in['code'] ?? ''));
        $name  = trim($in['name'] ?? '');
        $unit  = trim($in['unit'] ?? 'Kg');
        $price = (int)($in['price_per_unit'] ?? 0);
        if ($code === '' || $name === '' || $price <= 0) {
            jsonOut(['error' => 'Kode, nama layanan, dan harga (> 0) wajib diisi.'], 400);
        }

        if ($method === 'POST') {
            $pdo->prepare("INSERT INTO services (code, name, unit, price_per_unit) VALUES (?, ?, ?, ?)")
                ->execute([$code, $name, $unit, $price]);
            jsonOut(['id' => (int)$pdo->lastInsertId()], 201);
        }

        if ($method === 'PUT' && $id > 0) {
            $pdo->prepare("UPDATE services SET code = ?, name = ?, unit = ?, price_per_unit = ? WHERE id = ?")
                ->execute([$code, $name, $unit, $price, $id]);
            jsonOut(['updated' => true]);
        }
    }

    // =========================================================
    // 3. ROUTE: ORDERS (CRUD TIKET CUCIAN)
    // =========================================================
    $user  = requireAuth();
    $actor = "{$user['code']} ({$user['name']})";

    if ($method === 'GET') {
        if ($action === 'health') {
            $pdo->query("SELECT 1");
            jsonOut(['status' => 'ONLINE']);
        }

        if ($action === 'summary') {
            $stats = $pdo->query("
                SELECT
                    COUNT(CASE WHEN status_step < 7 THEN 1 END) AS active_total,
                    COUNT(CASE WHEN status_step IN (0, 1) THEN 1 END) AS need_weighing,
                    COUNT(CASE WHEN status_step IN (2, 3, 4, 5) THEN 1 END) AS in_process,
                    COUNT(CASE WHEN status_step = 3 THEN 1 END) AS washing_only,
                    COUNT(CASE WHEN status_step = 6 THEN 1 END) AS ready_pickup,
                    COUNT(CASE WHEN delivery_type = 'ANTAR_JEMPUT' AND status_step < 7 THEN 1 END) AS antar_jemput_total
                FROM orders
            ")->fetch();

            $overdue = $pdo->query("
                SELECT o.id, o.receipt_no, o.overdue_minutes, o.status_note, c.name AS customer_name
                FROM orders o JOIN customers c ON o.customer_id = c.id
                WHERE o.is_overdue = 1 AND o.status_step < 7
                ORDER BY o.overdue_minutes DESC
            ")->fetchAll();

            jsonOut(['stats' => $stats, 'overdueOrders' => $overdue]);
        }

        if ($id > 0) {
            $stmt = $pdo->prepare("
                SELECT o.*, c.name AS customer_name, c.phone AS customer_phone, s.name AS service_name, s.unit AS service_unit, s.price_per_unit
                FROM orders o
                JOIN customers c ON o.customer_id = c.id
                JOIN services s ON o.service_id = s.id
                WHERE o.id = ?
            ");
            $stmt->execute([$id]);
            $order = $stmt->fetch();
            if (!$order) jsonOut(['error' => 'Tiket tidak ditemukan.'], 404);

            $logs = $pdo->prepare("SELECT * FROM order_logs WHERE order_id = ? ORDER BY id DESC");
            $logs->execute([$id]);
            $order['logs'] = $logs->fetchAll();
            jsonOut($order);
        }

        // Filter & Pagination List
        $search = trim($_GET['search'] ?? '');
        $filter = strtoupper(trim($_GET['filter'] ?? 'ALL'));
        $page   = max(1, (int)($_GET['page'] ?? 1));
        $limit  = max(1, min(50, (int)($_GET['limit'] ?? 5)));

        $filterMap = [
            'ALL'          => 'o.status_step < 7',
            'BARU'         => 'o.status_step IN (0, 1)',
            'ANTAR_JEMPUT' => "o.delivery_type = 'ANTAR_JEMPUT' AND o.status_step < 7",
            'DICUCI'       => 'o.status_step = 3',
            'SIAP'         => 'o.status_step = 6',
            'OVERDUE'      => 'o.is_overdue = 1 AND o.status_step < 7',
        ];
        $where  = [$filterMap[$filter] ?? 'o.status_step < 7'];
        $params = [];

        if ($search !== '') {
            $where[] = "(o.receipt_no LIKE ? OR c.name LIKE ? OR c.phone LIKE ? OR o.pickup_address LIKE ?)";
            $params  = array_fill(0, 4, "%$search%");
        }
        $whereSql = 'WHERE ' . implode(' AND ', $where);

        $countStmt = $pdo->prepare("SELECT COUNT(*) FROM orders o JOIN customers c ON o.customer_id = c.id $whereSql");
        $countStmt->execute($params);
        $total      = (int)$countStmt->fetchColumn();
        $totalPages = max(1, (int)ceil($total / $limit));
        $safePage   = min($page, $totalPages);
        $offset     = ($safePage - 1) * $limit;

        $dataStmt = $pdo->prepare("
            SELECT o.*, c.name AS customer_name, c.phone AS customer_phone, s.name AS service_name, s.unit AS service_unit, s.price_per_unit
            FROM orders o
            JOIN customers c ON o.customer_id = c.id
            JOIN services s ON o.service_id = s.id
            $whereSql ORDER BY o.id ASC LIMIT $limit OFFSET $offset
        ");
        $dataStmt->execute($params);

        jsonOut([
            'data' => $dataStmt->fetchAll(),
            'pagination' => [
                'page'       => $safePage,
                'total'      => $total,
                'totalPages' => $totalPages,
                'from'       => $total === 0 ? 0 : $offset + 1,
                'to'         => min($offset + $limit, $total)
            ]
        ]);
    }

    if ($method === 'POST') {
        $in       = jsonIn();
        $name     = trim($in['customer_name'] ?? '');
        $phone    = trim($in['customer_phone'] ?? '');
        $srvId    = (int)($in['service_id'] ?? 0);
        $delType  = ($in['delivery_type'] ?? 'DROP_OFF') === 'ANTAR_JEMPUT' ? 'ANTAR_JEMPUT' : 'DROP_OFF';
        $address  = $delType === 'ANTAR_JEMPUT' ? trim($in['pickup_address'] ?? '') : null;

        if ($name === '' || $phone === '' || $srvId <= 0) {
            jsonOut(['error' => 'Nama, WhatsApp, dan layanan wajib diisi.'], 400);
        }
        if ($delType === 'ANTAR_JEMPUT' && !$address) {
            jsonOut(['error' => 'Alamat wajib diisi untuk layanan Antar-Jemput.'], 400);
        }

        $srv = $pdo->prepare("SELECT * FROM services WHERE id = ?");
        $srv->execute([$srvId]);
        $service = $srv->fetch();
        if (!$service) jsonOut(['error' => 'Layanan tidak ditemukan.'], 400);

        $weight    = max(0.1, (float)($in['weight'] ?? 1));
        $isEst     = !empty($in['is_estimated_weight']) ? 1 : 0;
        $step      = (int)($in['status_step'] ?? ($delType === 'ANTAR_JEMPUT' ? 0 : 1));
        $note      = trim($in['status_note'] ?? '') ?: ($step === 0 ? 'Menunggu Kurir Jemput' : ($step === 3 ? 'Antrean Mesin Cuci' : 'Menunggu Timbang Ulang'));
        $payStatus = ($in['payment_status'] ?? 'BELUM') === 'LUNAS' ? 'LUNAS' : 'BELUM';
        $payMethod = $payStatus === 'LUNAS' ? ($in['payment_method'] ?? 'Tunai') : null;
        $fee       = $delType === 'ANTAR_JEMPUT' ? getDeliveryFee($pdo) : 0;
        $total     = (int)round($weight * $service['price_per_unit']) + $fee;

        $pdo->beginTransaction();
        $custId    = upsertCustomer($pdo, $name, $phone, $address);
        $receiptNo = nextReceiptNo($pdo);

        $pdo->prepare("
            INSERT INTO orders (receipt_no, customer_id, service_id, delivery_type, pickup_address, delivery_fee, weight, is_estimated_weight, status_step, status_note, payment_status, payment_method, total_price, notes, time_label)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ")->execute([$receiptNo, $custId, $srvId, $delType, $address, $fee, $weight, $isEst, $step, $note, $payStatus, $payMethod, $total, trim($in['notes'] ?? ''), 'Hari ini, ' . date('H:i')]);

        $orderId = (int)$pdo->lastInsertId();
        $pdo->prepare("INSERT INTO order_logs (order_id, action, actor) VALUES (?, ?, ?)")
            ->execute([$orderId, "Tiket $receiptNo [$delType] diterima ({$service['name']}, $weight {$service['unit']})", $actor]);

        $pdo->commit();
        jsonOut(['id' => $orderId, 'receipt_no' => $receiptNo], 201);
    }

    if ($method === 'PUT' && $id > 0) {
        $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
        $stmt->execute([$id]);
        $cur = $stmt->fetch();
        if (!$cur) jsonOut(['error' => 'Tiket tidak ditemukan.'], 404);

        $in      = jsonIn();
        $delType = isset($in['delivery_type']) ? ($in['delivery_type'] === 'ANTAR_JEMPUT' ? 'ANTAR_JEMPUT' : 'DROP_OFF') : $cur['delivery_type'];
        $address = $delType === 'ANTAR_JEMPUT' ? (array_key_exists('pickup_address', $in) ? trim((string)$in['pickup_address']) : $cur['pickup_address']) : null;
        $fee     = $delType === 'ANTAR_JEMPUT' ? (isset($in['delivery_fee']) ? max(0, (int)$in['delivery_fee']) : (int)$cur['delivery_fee']) : 0;

        $pdo->beginTransaction();
        $custId = (!empty($in['customer_name']) && !empty($in['customer_phone']))
            ? upsertCustomer($pdo, $in['customer_name'], $in['customer_phone'], $address)
            : (int)$cur['customer_id'];

        $step      = isset($in['status_step']) ? (int)$in['status_step'] : (int)$cur['status_step'];
        $note      = isset($in['status_note']) ? trim($in['status_note']) : $cur['status_note'];
        $payStatus = $in['payment_status'] ?? $cur['payment_status'];
        $payMethod = $payStatus === 'LUNAS' ? ($in['payment_method'] ?? $cur['payment_method'] ?? 'Tunai') : null;
        $overdue   = isset($in['is_overdue']) ? ($in['is_overdue'] ? 1 : 0) : (int)$cur['is_overdue'];

        $pdo->prepare("
            UPDATE orders SET
                customer_id = ?, service_id = ?, delivery_type = ?, pickup_address = ?, delivery_fee = ?,
                weight = ?, is_estimated_weight = ?, status_step = ?, status_note = ?,
                payment_status = ?, payment_method = ?, total_price = ?, is_overdue = ?,
                overdue_minutes = ?, notes = ?
            WHERE id = ?
        ")->execute([
            $custId,
            (int)($in['service_id'] ?? $cur['service_id']),
            $delType,
            $address,
            $fee,
            (float)($in['weight'] ?? $cur['weight']),
            isset($in['is_estimated_weight']) ? ($in['is_estimated_weight'] ? 1 : 0) : (int)$cur['is_estimated_weight'],
            $step,
            $note,
            $payStatus,
            $payMethod,
            (int)round((float)($in['total_price'] ?? $cur['total_price'])),
            $overdue,
            $overdue ? ((int)$cur['overdue_minutes'] ?: 15) : 0,
            isset($in['notes']) ? trim($in['notes']) : $cur['notes'],
            $id
        ]);

        $pdo->prepare("INSERT INTO order_logs (order_id, action, actor) VALUES (?, ?, ?)")
            ->execute([$id, "Update -> Tahap [$step/7] ($note) | Bayar: $payStatus", $actor]);

        $pdo->commit();
        jsonOut(['updated' => true]);
    }

    if ($method === 'DELETE' && $id > 0) {
        $pdo->prepare("DELETE FROM orders WHERE id = ?")->execute([$id]);
        jsonOut(['deleted' => true]);
    }

    jsonOut(['error' => 'Permintaan tidak valid.'], 400);
} catch (PDOException $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    $msg = $e->getCode() === '23000'
        ? 'Data tidak bisa dihapus/disimpan karena duplikat atau sedang dipakai transaksi.'
        : $e->getMessage();
    jsonOut(['error' => $msg], 400);
}
