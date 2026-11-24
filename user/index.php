<?php
require __DIR__ . '/includes/guard.php';
require_once __DIR__ . '/../config/database.php';

$activeNav = 'beranda';
$cust = $_SESSION['customer'];

// Ambil pesanan aktif terbaru (status_step 0..6)
$stmtActive = $pdo->prepare("
    SELECT o.*, s.name AS service_name, s.unit
    FROM orders o
    JOIN services s ON s.id = o.service_id
    WHERE o.customer_id = ? AND o.status_step < 7
    ORDER BY o.id DESC
    LIMIT 1
");
$stmtActive->execute([$cust['id']]);
$activeOrder = $stmtActive->fetch();

// Hitung total pesanan aktif
$stmtCount = $pdo->prepare("SELECT COUNT(*) FROM orders WHERE customer_id = ? AND status_step < 7");
$stmtCount->execute([$cust['id']]);
$activeCount = (int)$stmtCount->fetchColumn();

// Ambil 2 riwayat selesai terakhir (status_step = 7)
$stmtHist = $pdo->prepare("
    SELECT o.*, s.name AS service_name, s.unit
    FROM orders o
    JOIN services s ON s.id = o.service_id
    WHERE o.customer_id = ? AND o.status_step = 7
    ORDER BY o.id DESC
    LIMIT 2
");
$stmtHist->execute([$cust['id']]);
$recentOrders = $stmtHist->fetchAll();

$stageNames = [
    0 => 'MENUNGGU JEMPUT KURIR',
    1 => 'MENUNGGU TIMBANG',
    2 => 'ANTRIAN CUCI',
    3 => 'SEDANG DICUCI',
    4 => 'PENGERINGAN',
    5 => 'SETRIKA & PACKING',
    6 => 'SIAP DIAMBIL / DIANTAR',
    7 => 'SELESAI'
];
$shortLabels = [1 => 'Timbang', 2 => 'Antre', 3 => 'Cuci', 4 => 'Kering', 5 => 'Setrika', 6 => 'Siap', 7 => 'Selesai'];
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
    <title>Dashboard Pelanggan - Yara Yara Laundry</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet">
    <style data-purpose="layout">
        body {
            background-color: #f4f5f7;
            color: #181c28;
            font-family: 'JetBrains Mono', ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            -webkit-font-smoothing: antialiased;
        }
    </style>
</head>

<body class="min-h-screen pb-24">
    <div class="max-w-md mx-auto min-h-screen flex flex-col bg-[#f4f5f7] border-x border-slate-200">
        <?php require __DIR__ . '/includes/header.php'; ?>

        <main class="flex-1 px-4 pt-5 space-y-5" data-purpose="main-dashboard">
            <section class="bg-white border border-slate-200 p-4" data-purpose="greeting-card">
                <h1 class="text-xl font-bold tracking-tight text-[#181c28]">
                    Halo, <?= htmlspecialchars($cust['name']) ?>
                </h1>
            </section>

            <section data-purpose="create-order-action">
                <a href="pesan.php" class="w-full bg-[#181c28] hover:bg-slate-800 active:bg-slate-900 text-white py-3.5 px-4 flex items-center justify-center gap-2.5 text-xs font-bold tracking-wider uppercase transition-colors">
                    <span class="inline-flex items-center justify-center w-4 h-4 border border-white text-[11px] leading-none">+</span>
                    <span>[+ BUAT PESANAN LAUNDRY BARU]</span>
                </a>
            </section>

            <?php if ($activeOrder): ?>
                <?php
                $curStage = (int)$activeOrder['status_step'];
                $qtyText = strtoupper($activeOrder['unit']) === 'KG'
                    ? number_format((float)$activeOrder['weight'], 1) . ' Kg'
                    : (int)$activeOrder['weight'] . ' Pcs';
                ?>
                <section class="bg-white border border-slate-200 p-4 space-y-4" data-purpose="active-order-card">
                    <div class="bg-[#eef2f9] px-3 py-1.5 flex items-center justify-between text-[11px]">
                        <span class="font-bold tracking-wider text-[#181c28]">PESANAN AKTIF (<?= $activeCount ?>)</span>
                        <span class="text-slate-500">[LIVE TRACKING]</span>
                    </div>

                    <div class="flex items-start justify-between gap-2">
                        <div class="text-xl font-bold tracking-tight text-[#181c28] leading-tight">
                            <?= htmlspecialchars($activeOrder['receipt_no']) ?>
                        </div>
                        <div class="bg-[#eef2f9] px-2 py-1 text-[10px] font-bold text-[#181c28] text-right leading-snug">
                            [<?= $stageNames[$curStage] ?? 'DIPROSES' ?> - TAHAP <?= max(1, $curStage) ?> DARI 7]
                        </div>
                    </div>

                    <div class="space-y-2.5 text-xs">
                        <div>
                            <div class="text-[10px] text-slate-500 uppercase tracking-wider">LAYANAN:</div>
                            <div class="font-bold text-[#181c28] text-sm"><?= htmlspecialchars($activeOrder['service_name']) ?> (<?= $qtyText ?>)</div>
                        </div>
                        <div>
                            <div class="text-[10px] text-slate-500 uppercase tracking-wider">STATUS OPERASIONAL:</div>
                            <div class="text-[#181c28]"><?= htmlspecialchars($activeOrder['status_note']) ?> (<?= htmlspecialchars($activeOrder['time_label']) ?>)</div>
                        </div>
                    </div>

                    <div class="bg-[#f4f5f7] p-3 border border-slate-200" data-purpose="workflow-progress">
                        <div class="text-[10px] text-slate-500 mb-2">[PROGRES_WORKFLOW_TAHAP]</div>
                        <div class="grid grid-cols-7 gap-1 text-center">
                            <?php for ($st = 1; $st <= 7; $st++): ?>
                                <div>
                                    <div class="h-1.5 <?= $st <= $curStage ? 'bg-[#181c28]' : 'bg-slate-300' ?> mb-1"></div>
                                    <?php if ($st === $curStage): ?>
                                        <span class="text-[9px] font-bold text-[#181c28] block truncate">[<?= $shortLabels[$st] ?>]</span>
                                    <?php elseif ($st < $curStage): ?>
                                        <span class="text-[9px] font-medium text-[#181c28] block truncate"><?= $shortLabels[$st] ?></span>
                                    <?php else: ?>
                                        <span class="text-[9px] text-slate-400 block truncate"><?= $shortLabels[$st] ?></span>
                                    <?php endif; ?>
                                </div>
                            <?php endfor; ?>
                        </div>
                    </div>

                    <a href="lacak.php?code=<?= urlencode($activeOrder['receipt_no']) ?>" class="w-full bg-[#eef2f9] hover:bg-slate-200 text-[#181c28] py-2.5 px-3 flex items-center justify-between text-xs font-bold tracking-wider transition-colors">
                        <span>[LACAK STATUS PESANAN]</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="9"></circle>
                            <circle cx="12" cy="12" r="4"></circle>
                            <circle cx="12" cy="12" fill="currentColor" r="1"></circle>
                        </svg>
                    </a>
                </section>
            <?php else: ?>
                <section class="bg-white border border-slate-200 p-4 text-center space-y-2">
                    <div class="text-xs font-bold text-[#181c28]">[BELUM ADA PESANAN AKTIF]</div>
                    <p class="text-[11px] text-slate-500">Klik tombol [+ BUAT PESANAN LAUNDRY BARU] di atas untuk mulai mencuci.</p>
                </section>
            <?php endif; ?>

            <section class="space-y-3" data-purpose="recent-history">
                <div class="flex items-center justify-between">
                    <h2 class="text-xs font-bold tracking-wider text-[#181c28] uppercase">
                        RIWAYAT PESANAN TERAKHIR
                    </h2>
                    <a class="text-[11px] text-slate-600 underline hover:text-[#181c28]" href="riwayat.php">
                        [LIHAT SEMUA]
                    </a>
                </div>

                <?php if (empty($recentOrders)): ?>
                    <div class="bg-white border border-slate-200 p-3.5 text-center text-[11px] text-slate-500">
                        Belum ada riwayat pesanan yang selesai.
                    </div>
                <?php else: ?>
                    <?php foreach ($recentOrders as $ro): ?>
                        <?php
                        $rQty = strtoupper($ro['unit']) === 'KG'
                            ? number_format((float)$ro['weight'], 1) . ' Kg'
                            : (int)$ro['weight'] . ' Pcs';
                        ?>
                        <article class="bg-white border border-slate-200 p-3.5 space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-bold text-[#181c28]"><?= htmlspecialchars($ro['receipt_no']) ?></span>
                                <span class="bg-[#eef2f9] px-2 py-0.5 text-[10px] font-bold text-[#181c28]">[SELESAI]</span>
                            </div>
                            <div class="text-xs text-[#181c28]">
                                <?= htmlspecialchars($ro['service_name']) ?> (<?= $rQty ?>)
                            </div>
                            <div class="flex items-center justify-between pt-1 text-[11px] text-slate-500 border-t border-slate-100">
                                <div>
                                    <span><?= date('d M Y', strtotime($ro['created_at'])) ?></span>
                                    <span class="mx-1">|</span>
                                    <span class="font-bold text-[#181c28]">Rp <?= number_format((int)$ro['total_price'], 0, ',', '.') ?></span>
                                </div>
                                <a class="font-bold text-[#181c28] hover:underline" href="riwayat.php#<?= urlencode(ltrim($ro['receipt_no'], '#')) ?>">[LIHAT DETAIL]</a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>
            </section>
        </main>

        <?php require __DIR__ . '/includes/bottom-nav.php'; ?>
    </div>
</body>

</html>