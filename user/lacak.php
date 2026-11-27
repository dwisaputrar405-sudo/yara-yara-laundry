<?php
require __DIR__ . '/includes/guard.php';
require_once __DIR__ . '/../config/database.php';

$activeNav = 'lacak';
$cust = $_SESSION['customer'];

// Ambil semua pesanan aktif user ini (untuk switcher jika punya > 1 pesanan aktif)
$stmtAll = $pdo->prepare("
    SELECT o.id, o.receipt_no, s.name AS service_name
    FROM orders o
    JOIN services s ON s.id = o.service_id
    WHERE o.customer_id = ? AND o.status_step < 7
    ORDER BY o.id DESC
");
$stmtAll->execute([$cust['id']]);
$activeOrders = $stmtAll->fetchAll();

// Ambil detail pesanan berdasarkan parameter ?code=... atau pesanan aktif terbaru
$reqCode = trim($_GET['code'] ?? '');
if ($reqCode !== '') {
    $stmt = $pdo->prepare("
        SELECT o.*, s.name AS service_name, s.unit, s.code AS service_code, s.price_per_unit
        FROM orders o
        JOIN services s ON s.id = o.service_id
        WHERE o.customer_id = ? AND o.receipt_no = ?
        LIMIT 1
    ");
    $stmt->execute([$cust['id'], $reqCode]);
    $order = $stmt->fetch();
} else {
    $stmt = $pdo->prepare("
        SELECT o.*, s.name AS service_name, s.unit, s.code AS service_code, s.price_per_unit
        FROM orders o
        JOIN services s ON s.id = o.service_id
        WHERE o.customer_id = ? AND o.status_step < 7
        ORDER BY o.id DESC
        LIMIT 1
    ");
    $stmt->execute([$cust['id']]);
    $order = $stmt->fetch();
}

$stageBadges = [
    0 => 'MENUNGGU JEMPUT KURIR',
    1 => 'MENUNGGU TIMBANG',
    2 => 'ANTRIAN CUCI',
    3 => 'SEDANG DICUCI',
    4 => 'PENGERINGAN',
    5 => 'SETRIKA & PACKING',
    6 => 'SIAP DIAMBIL / DIANTAR',
    7 => 'SELESAI'
];

$allWorkflowSteps = [
    1 => ['title' => 'DITERIMA & TIMBANG',  'desc' => 'Cucian diterima di outlet & ditimbang'],
    2 => ['title' => 'ANTRIAN CUCI',        'desc' => 'Menunggu giliran masuk mesin cuci'],
    3 => ['title' => 'DICUCI',              'desc' => 'Proses pencucian sesuai jenis layanan'],
    4 => ['title' => 'DIKERINGKAN',         'desc' => 'Proses pengeringan mesin tumbler'],
    5 => ['title' => 'DISETRIKA & PACKING', 'desc' => 'Penyetrikaan uap & pengemasan rapi'],
    6 => ['title' => 'SIAP DIAMBIL/ANTAR',  'desc' => 'Siap diambil di rak / diantar kurir'],
    7 => ['title' => 'SELESAI',             'desc' => 'Pesanan telah diterima pelanggan']
];
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
    <title>Lacak Pesanan - Yara Yara Laundry</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet">
    <style data-purpose="custom-styles">
        body {
            font-family: 'JetBrains Mono', ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            background-color: #EEF4FA;
            color: #0F2854;
            -webkit-tap-highlight-color: transparent;
        }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>

<body class="min-h-screen pb-24">
    <div class="max-w-md mx-auto min-h-screen flex flex-col bg-[#EEF4FA] border-x border-[#DCEAF7]">
        <?php require __DIR__ . '/includes/header.php'; ?>

        <main class="flex-1 px-4 pt-4 space-y-4">
            <?php if (count($activeOrders) > 1): ?>
                <div class="flex gap-2 overflow-x-auto no-scrollbar pb-1">
                    <?php foreach ($activeOrders as $ao): ?>
                        <?php $isCurrent = $order && $ao['receipt_no'] === $order['receipt_no']; ?>
                        <a href="lacak.php?code=<?= urlencode($ao['receipt_no']) ?>"
                           class="px-2.5 py-1.5 text-[11px] font-bold shrink-0 border <?= $isCurrent ? 'bg-[#0F2854] text-white border-[#0F2854]' : 'bg-[#F8FBFF] text-[#0F2854] border-[#C9D9EA]' ?>">
                            <?= htmlspecialchars($ao['receipt_no']) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if (!$order): ?>
                <section class="bg-[#F8FBFF] border border-[#C9D9EA] p-6 text-center space-y-3">
                    <div class="text-xs font-bold text-[#0F2854]">[TIDAK ADA PESANAN AKTIF]</div>
                    <p class="text-[11px] text-slate-500">Saat ini kamu belum memiliki pesanan yang sedang diproses.</p>
                    <a href="pesan.php" class="inline-block bg-[#0F2854] text-white px-4 py-2 text-xs font-bold">
                        [+ BUAT PESANAN BARU]
                    </a>
                </section>
            <?php else: ?>
                <?php
                $curStep = (int)$order['status_step'];
                $srvUpper = strtoupper((string)$order['service_name']);
                $isIronOnly = (strpos($srvUpper, 'SETRIKA') !== false && strpos($srvUpper, 'CUCI') === false);
                $isFoldOnly = (strpos($srvUpper, 'LIPAT') !== false && strpos($srvUpper, 'SETRIKA') === false);

                $skippedSteps = [];
                if ($isIronOnly) {
                    $skippedSteps = [2, 3, 4];
                } elseif ($isFoldOnly) {
                    $skippedSteps = [5];
                }

                $workflowSteps = [];
                foreach ($allWorkflowSteps as $stepNum => $info) {
                    if (!in_array($stepNum, $skippedSteps, true)) {
                        $workflowSteps[$stepNum] = $info;
                    }
                }

                $totalVisualSteps = count($workflowSteps);
                $stepKeys = array_keys($workflowSteps);
                $currentPos = 1;
                if ($curStep === 0) {
                    $currentPos = 1;
                } else {
                    foreach ($stepKeys as $idx => $sKey) {
                        if ($curStep >= $sKey) {
                            $currentPos = $idx + 1;
                        }
                    }
                }

                $pct = $curStep === 0 ? 5 : round(($currentPos / max(1, $totalVisualSteps)) * 100, 1);

                $qtyNum = (float)$order['weight'];
                $qtyText = strtoupper($order['unit']) === 'KG'
                    ? number_format($qtyNum, 1) . ' Kg'
                    : (int)$qtyNum . ' ' . $order['unit'];

                $totalPrice = (int)$order['total_price'];
                $deliveryFee = (int)($order['delivery_fee'] ?? 0);
                $unitPrice = (int)($order['price_per_unit'] ?? ($qtyNum > 0 ? round(($totalPrice - $deliveryFee) / $qtyNum) : 0));

                $isDelivery = !empty($order['pickup_address']) || (isset($order['delivery_type']) && stripos((string)$order['delivery_type'], 'antar') !== false);
                $methodText = isset($order['delivery_type']) && trim((string)$order['delivery_type']) !== ''
                    ? strtoupper(str_replace('_', '-', (string)$order['delivery_type']))
                    : ($isDelivery ? 'ANTAR-JEMPUT' : 'DIAMBIL SENDIRI');
                $payMethod = !empty($order['payment_method']) ? ' - ' . strtoupper((string)$order['payment_method']) : '';
                ?>

                <!-- 1. Nomor Pelacakan -->
                <section class="bg-[#F8FBFF] border border-[#C9D9EA] p-4 flex items-center justify-between gap-2">
                    <div>
                        <div class="text-[10px] text-slate-500 tracking-wider mb-1">NO. PELACAKAN SISTEM</div>
                        <div class="text-2xl font-extrabold tracking-tight text-[#0F2854]">
                            <?= htmlspecialchars($order['receipt_no']) ?>
                        </div>
                    </div>
                    <button onclick="window.location.reload()" type="button" class="border border-[#B8CDE2] bg-[#EEF4FA] hover:bg-[#DCEAF7] active:bg-[#C9D9EA] px-3 py-2 text-xs font-bold flex items-center gap-1.5 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                        <span>MUAT ULANG</span>
                    </button>
                </section>

                <!-- 2. Status Aktif & Progress Bar -->
                <section class="bg-[#F8FBFF] border border-[#C9D9EA] p-4 space-y-3">
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-xs font-bold text-slate-600">STATUS AKTIF</span>
                        <span class="bg-[#0F2854] text-white text-[10px] font-bold px-2.5 py-1 tracking-wider">
                            <?= $stageBadges[$curStep] ?? 'DIPROSES' ?>
                        </span>
                    </div>

                    <div class="bg-[#E2EEF9] border border-[#DCEAF7] p-3 space-y-2.5">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <div class="text-[10px] text-slate-500">LAYANAN & BERAT</div>
                                <div class="text-xs font-bold text-[#0F2854]">
                                    <?= htmlspecialchars($order['service_name']) ?> (<?= $qtyText ?><?= (int)$order['is_estimated_weight'] === 1 ? ' - Est.' : '' ?>)
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-xs font-extrabold text-[#0F2854]">Rp <?= number_format($totalPrice, 0, ',', '.') ?></div>
                                <div class="text-[10px] font-bold <?= $order['payment_status'] === 'LUNAS' ? 'text-emerald-700' : 'text-amber-700' ?>">
                                    <?= $order['payment_status'] === 'LUNAS' ? 'LUNAS' : 'BELUM BAYAR' ?>
                                </div>
                            </div>
                        </div>

                        <div class="w-full h-2.5 bg-[#C9D9EA] overflow-hidden">
                            <div class="h-full bg-[#4988C4] transition-all duration-300" style="width: <?= $pct ?>%"></div>
                        </div>

                        <div class="flex items-center justify-between text-[11px]">
                            <span class="font-semibold text-slate-700">TAHAP <?= $currentPos ?> DARI <?= $totalVisualSteps ?></span>
                            <span class="font-bold text-[#0F2854]">[<?= $pct ?>% SELESAI]</span>
                        </div>
                    </div>

                    <div class="text-[11px] bg-[#F5F9FE] border border-[#DCEAF7] px-3 py-2">
                        <span class="text-slate-500">Catatan Kasir:</span>
                        <span class="font-bold text-[#0F2854]"><?= htmlspecialchars($order['status_note']) ?></span>
                    </div>
                </section>

                <!-- 3. Diagram Alur Proses (Dinamis Sesuai Layanan) -->
                <section class="bg-[#F8FBFF] border border-[#C9D9EA] p-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-[#0F2854] tracking-wide">TRACKING</span>
                        <?php if ($isIronOnly): ?>
                            <span class="text-[9px] font-bold px-1.5 py-0.5 bg-[#DCEAF7] text-slate-700">KHUSUS SETRIKA</span>
                        <?php endif; ?>
                    </div>

                    <?php if ($curStep === 0): ?>
                        <div class="border-2 border-[#0F2854] bg-[#E2EEF9] p-3 mb-2">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-extrabold">[0] MENUNGGU JEMPUT KURIR</span>
                                <span class="bg-[#4988C4] text-white text-[9px] font-bold px-1.5 py-0.5">[BERJALAN]</span>
                            </div>
                            <p class="text-[11px] text-slate-600 mt-1">Kurir sedang menjadwalkan penjemputan ke <?= htmlspecialchars($order['pickup_address'] ?? 'alamatmu') ?>.</p>
                        </div>
                    <?php endif; ?>

                    <div class="space-y-3">
                        <?php $visualNum = 0; ?>
                        <?php foreach ($workflowSteps as $stepNum => $info): ?>
                            <?php
                            $visualNum++;
                            $isDone    = $curStep > $stepNum || ($curStep === 7 && $stepNum === 7);
                            $isCurrent = $curStep === $stepNum && $curStep < 7;
                            $stepLabel = $visualNum . '. ' . $info['title'];
                            ?>
                            <div class="<?= $isCurrent ? 'border-2 border-[#0F2854] bg-[#E2EEF9] p-2.5' : 'flex items-start gap-3' ?>">
                                <?php if ($isCurrent): ?>
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="flex items-start gap-2.5">
                                            <span class="w-5 h-5 bg-[#0F2854] text-white text-[10px] font-bold flex items-center justify-center shrink-0 mt-0.5">[/]</span>
                                            <div>
                                                <div class="text-xs font-extrabold text-[#0F2854]"><?= $stepLabel ?></div>
                                                <div class="text-[11px] text-slate-600 mt-0.5"><?= htmlspecialchars($order['status_note']) ?></div>
                                            </div>
                                        </div>
                                        <span class="bg-[#4988C4] text-white text-[9px] font-bold px-1.5 py-0.5 shrink-0">BERJALAN</span>
                                    </div>
                                <?php else: ?>
                                    <span class="w-5 h-5 <?= $isDone ? 'bg-[#0F2854] text-white' : 'border border-[#B8CDE2] bg-[#F8FBFF] text-slate-400' ?> text-[10px] font-bold flex items-center justify-center shrink-0 mt-0.5">
                                        <?= $isDone ? '[x]' : '[ ]' ?>
                                    </span>
                                    <div class="flex-1 flex items-start justify-between gap-2">
                                        <div>
                                            <div class="text-xs font-bold <?= $isDone ? 'text-[#0F2854]' : 'text-slate-400' ?>"><?= $stepLabel ?></div>
                                            <div class="text-[10px] <?= $isDone ? 'text-slate-500' : 'text-slate-400' ?>"><?= $info['desc'] ?></div>
                                        </div>
                                        <span class="text-[9px] font-bold px-1.5 py-0.5 shrink-0 <?= $isDone ? 'bg-[#E2EEF9] text-[#0F2854]' : 'text-slate-400' ?>">
                                            <?= $isDone ? 'SELESAI' : 'MENUNGGU' ?>
                                        </span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>

                <!-- 4. Rincian Tagihan -->
                <section class="bg-[#F8FBFF] border border-[#C9D9EA] p-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-[#0F2854] tracking-wide">RINCIAN TAGIHAN</span>
                        <span class="text-[10px] text-slate-500 tracking-wider">GERAI UTAMA</span>
                    </div>

                    <div class="bg-[#E2EEF9] border border-[#DCEAF7] p-3.5 space-y-2.5 text-xs">
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-[11px] text-slate-500">LAYANAN</span>
                            <span class="font-bold text-[#0F2854] text-right"><?= strtoupper(htmlspecialchars($order['service_name'])) ?></span>
                        </div>

                        <div class="flex items-center justify-between gap-2">
                            <span class="text-[11px] text-slate-500">BEBAN</span>
                            <span class="font-bold text-[#0F2854] text-right">
                                <?= strtoupper($qtyText) ?><?= $unitPrice > 0 ? ' &times; RP ' . number_format($unitPrice, 0, ',', '.') : '' ?><?= (int)$order['is_estimated_weight'] === 1 ? ' (EST.)' : '' ?>
                            </span>
                        </div>

                        <div class="flex items-center justify-between gap-2">
                            <span class="text-[11px] text-slate-500">METODE</span>
                            <span class="font-bold text-[#0F2854] text-right"><?= htmlspecialchars($methodText) ?></span>
                        </div>

                        <div class="flex items-center justify-between gap-2">
                            <span class="text-[11px] text-slate-500">STATUS</span>
                            <span class="border border-[#B8CDE2] bg-[#F8FBFF] px-2 py-0.5 text-[10px] font-bold <?= $order['payment_status'] === 'LUNAS' ? 'text-emerald-700' : 'text-amber-700' ?>">
                                [<?= $order['payment_status'] === 'LUNAS' ? 'LUNAS' . htmlspecialchars($payMethod) : 'BELUM BAYAR' ?>]
                            </span>
                        </div>

                        <div class="border-t border-[#C9D9EA] pt-2.5 mt-1 flex items-center justify-between gap-2">
                            <span class="text-[11px] font-bold text-[#0F2854]">TOTAL TAGIHAN</span>
                            <span class="text-base font-extrabold text-[#0F2854]">RP <?= number_format($totalPrice, 0, ',', '.') ?></span>
                        </div>
                    </div>
                </section>
            <?php endif; ?>
        </main>

        <?php require __DIR__ . '/includes/bottom-nav.php'; ?>
    </div>
</body>

</html>