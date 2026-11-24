<?php
require __DIR__ . '/includes/guard.php';
require_once __DIR__ . '/../config/database.php';

$activeNav = 'riwayat';
$cust = $_SESSION['customer'];

// Ambil semua pesanan milik pelanggan ini (aktif & selesai)
$stmt = $pdo->prepare("
    SELECT o.*, s.name AS service_name, s.unit, s.code AS service_code
    FROM orders o
    JOIN services s ON s.id = o.service_id
    WHERE o.customer_id = ?
    ORDER BY o.id DESC
");
$stmt->execute([$cust['id']]);
$allOrders = $stmt->fetchAll();

$activeOrders = [];
$doneOrders   = [];
foreach ($allOrders as $ord) {
    if ((int)$ord['status_step'] < 7) {
        $activeOrders[] = $ord;
    } else {
        $doneOrders[] = $ord;
    }
}

$totalCount  = count($allOrders);
$activeCount = count($activeOrders);
$doneCount   = count($doneOrders);

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
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
    <title>Riwayat Pesanan &amp; Resi Digital - YARA YARA</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet">
    <style data-purpose="typography-and-theme">
        body {
            font-family: 'JetBrains Mono', ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            background-color: #f4f5f7;
            color: #181c28;
            -webkit-font-smoothing: antialiased;
        }
    </style>
    <style data-purpose="custom-utilities">
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>

<body class="min-h-screen pb-24">
    <div class="max-w-md mx-auto min-h-screen bg-[#f4f5f7] border-x border-slate-200 flex flex-col">
        <?php require __DIR__ . '/includes/header.php'; ?>

        <!-- BEGIN: MainContent -->
        <main class="flex-1 px-4 py-5 space-y-6">
            <!-- BEGIN: PageTitle -->
            <section class="bg-white border border-slate-300 p-4" data-purpose="page-header">
                <h1 class="text-lg font-bold tracking-tight uppercase leading-snug">
                    RIWAYAT PESANAN &amp; RESI DIGITAL
                </h1>
                <p class="text-xs text-slate-600 mt-1.5 leading-relaxed">
                    Arsip transaksi laundry, status pembayaran, dan bukti resi digital.
                </p>
            </section>
            <!-- END: PageTitle -->

            <!-- BEGIN: SearchAndFilter -->
            <section class="space-y-3" data-purpose="search-and-filters">
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-700 mb-1.5" for="search-archive">
                        PENCARIAN ARSIP [KODE / TANGGAL / LAYANAN]
                    </label>
                    <div class="relative flex items-center bg-white border border-slate-400 focus-within:border-[#181c28]">
                        <span class="pl-3 text-slate-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </span>
                        <input class="w-full border-0 bg-transparent py-2.5 px-2.5 text-xs focus:ring-0 placeholder:text-slate-400" id="search-archive" placeholder="[CARI KODE / TANGGAL]" type="text">
                        <span class="pr-3 text-[10px] text-slate-400 font-semibold">[ENTER]</span>
                    </div>
                </div>

                <!-- Filter Tabs -->
                <div aria-label="Filter Status Pesanan" class="flex items-center gap-2 overflow-x-auto no-scrollbar" role="tablist">
                    <button aria-selected="true" class="filter-tab shrink-0 px-3 py-1.5 text-xs font-bold border border-[#181c28] bg-[#181c28] text-white" data-filter="all" role="tab" type="button">
                        [SEMUA (<?= $totalCount ?>)]
                    </button>
                    <button aria-selected="false" class="filter-tab shrink-0 px-3 py-1.5 text-xs font-bold border border-slate-300 bg-white text-[#181c28] hover:border-[#181c28]" data-filter="active" role="tab" type="button">
                        [BERJALAN (<?= $activeCount ?>)]
                    </button>
                    <button aria-selected="false" class="filter-tab shrink-0 px-3 py-1.5 text-xs font-bold border border-slate-300 bg-white text-[#181c28] hover:border-[#181c28]" data-filter="done" role="tab" type="button">
                        [SELESAI (<?= $doneCount ?>)]
                    </button>
                </div>
            </section>
            <!-- END: SearchAndFilter -->

            <?php if ($totalCount === 0): ?>
                <section class="bg-white border border-slate-300 p-6 text-center space-y-3">
                    <div class="text-xs font-bold text-[#181c28]">[BELUM ADA RIWAYAT TRANSAKSI]</div>
                    <p class="text-[11px] text-slate-500">Kamu belum pernah membuat pesanan laundry di akun ini.</p>
                    <a href="pesan.php" class="inline-block bg-[#181c28] text-white px-4 py-2 text-xs font-bold">
                        [+ BUAT PESANAN PERTAMA]
                    </a>
                </section>
            <?php else: ?>
                <!-- BEGIN: ActiveOrderSection -->
                <div class="space-y-4" data-status="active">
                    <?php foreach ($activeOrders as $ao): ?>
                        <?php
                        $step = (int)$ao['status_step'];
                        $displayStep = max(1, $step);
                        $pct = $step === 0 ? 5 : round(($step / 7) * 100);
                        $qtyText = strtoupper($ao['unit']) === 'KG'
                            ? number_format((float)$ao['weight'], 1) . ' Kg'
                            : (int)$ao['weight'] . ' Pcs';
                        $payLabel = $ao['payment_status'] === 'LUNAS'
                            ? '[LUNAS' . (!empty($ao['payment_method']) ? ' - ' . strtoupper($ao['payment_method']) : '') . ']'
                            : '[BELUM BAYAR]';
                        ?>
                        <section class="order-searchable bg-white border-2 border-[#181c28] p-4 space-y-3" data-purpose="active-order-card">
                            <div class="flex items-center justify-between border-b border-dashed border-slate-300 pb-2.5">
                                <div class="flex items-center gap-1.5 text-xs font-bold">
                                    <span class="inline-block w-2.5 h-2.5 bg-[#181c28]"></span>
                                    <span>[PESANAN SEDANG DIPROSES]</span>
                                </div>
                                <span class="bg-slate-200 text-[#181c28] text-[10px] font-bold px-2 py-0.5 uppercase">
                                    [LIVE TRACKING]
                                </span>
                            </div>

                            <div class="flex justify-between items-start text-xs">
                                <div>
                                    <p class="text-[10px] text-slate-500 uppercase">KODE PESANAN:</p>
                                    <p class="text-sm font-bold"><?= htmlspecialchars($ao['receipt_no']) ?></p>
                                </div>
                                <div class="text-right">
                                    <p class="text-[10px] text-slate-500 uppercase">TANGGAL MASUK:</p>
                                    <p class="font-semibold"><?= date('d M Y, H:i', strtotime($ao['created_at'])) ?></p>
                                </div>
                            </div>

                            <div class="border-t border-dashed border-slate-300 pt-2.5">
                                <p class="text-[10px] text-slate-500 uppercase">LAYANAN &amp; MUATAN:</p>
                                <p class="text-sm font-bold"><?= htmlspecialchars($ao['service_name']) ?> (<?= $qtyText ?><?= (int)$ao['is_estimated_weight'] === 1 ? ' - Est.' : '' ?>)</p>
                            </div>

                            <div class="bg-slate-100 border border-slate-300 p-2.5 space-y-1.5">
                                <div class="flex justify-between items-center text-[10px] font-bold">
                                    <span>STATUS TAHAPAN:</span>
                                    <span>[TAHAP <?= $displayStep ?> DARI 7]</span>
                                </div>
                                <div class="w-full h-2 bg-slate-300">
                                    <div class="h-full bg-[#181c28]" style="width: <?= $pct ?>%;"></div>
                                </div>
                                <div class="flex justify-between items-center text-[10px] text-slate-600 pt-0.5 gap-2">
                                    <span class="font-bold text-[#181c28]"><?= $stageNames[$step] ?? 'DIPROSES' ?></span>
                                    <span class="truncate"><?= htmlspecialchars($ao['status_note']) ?></span>
                                </div>
                            </div>

                            <div class="flex justify-between items-end pt-1">
                                <div>
                                    <p class="text-[10px] text-slate-500 uppercase">TOTAL BIAYA:</p>
                                    <p class="text-sm font-bold">RP <?= number_format((int)$ao['total_price'], 0, ',', '.') ?></p>
                                </div>
                                <div class="text-right">
                                    <p class="text-[10px] text-slate-500 uppercase mb-0.5">STATUS BAYAR:</p>
                                    <span class="bg-slate-200 text-[#181c28] text-[10px] font-bold px-2 py-0.5">
                                        <?= htmlspecialchars($payLabel) ?>
                                    </span>
                                </div>
                            </div>

                            <a href="lacak.php?code=<?= urlencode($ao['receipt_no']) ?>" class="block text-center w-full bg-[#181c28] text-white text-xs font-bold py-2.5 tracking-wider uppercase hover:bg-slate-800 transition-colors">
                                LACAK PROSES SEKARANG →
                            </a>
                        </section>
                    <?php endforeach; ?>
                </div>
                <!-- END: ActiveOrderSection -->

                <!-- BEGIN: CompletedHistorySection -->
                <section class="space-y-4" data-purpose="completed-orders-list" data-status="done">
                    <div class="flex items-center justify-between border-b border-slate-300 pb-2">
                        <div class="flex items-center gap-1.5 text-xs font-bold uppercase">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                            <span>ARSIP TRANSAKSI SELESAI</span>
                        </div>
                        <span class="text-[10px] text-slate-500 font-semibold">[<?= $doneCount ?> DOKUMEN]</span>
                    </div>

                    <?php if ($doneCount === 0): ?>
                        <div class="bg-white border border-slate-300 p-4 text-center text-xs text-slate-500">
                            Belum ada transaksi yang selesai.
                        </div>
                    <?php else: ?>
                        <?php foreach ($doneOrders as $idx => $do): ?>
                            <?php
                            $dQty = strtoupper($do['unit']) === 'KG'
                                ? number_format((float)$do['weight'], 1) . ' Kg'
                                : (int)$do['weight'] . ' Pcs';
                            $cleanId = ltrim($do['receipt_no'], '#');
                            $trxNo = 'REC-' . date('Ymd', strtotime($do['updated_at'])) . '-' . preg_replace('/\D+/', '', $cleanId);
                            $payMethod = strtoupper($do['payment_method'] ?: 'TUNAI OUTLET');
                            ?>
                            <article id="<?= htmlspecialchars($cleanId) ?>" class="order-searchable bg-white border <?= $idx === 0 ? 'border-slate-400' : 'border-slate-300' ?> p-4 space-y-3" data-purpose="digital-receipt-card">
                                <div class="flex justify-between items-start text-xs">
                                    <div>
                                        <p class="text-[10px] text-slate-500 uppercase">KODE:</p>
                                        <p class="text-sm font-bold"><?= htmlspecialchars($do['receipt_no']) ?></p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-[10px] text-slate-500 uppercase">TGL SELESAI:</p>
                                        <p class="font-semibold"><?= date('d M Y', strtotime($do['updated_at'])) ?></p>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-sm font-bold"><?= htmlspecialchars($do['service_name']) ?> (<?= $dQty ?>)</p>
                                    <p class="text-[10px] text-slate-500">[KODE LAYANAN: <?= htmlspecialchars($do['service_code']) ?>]</p>
                                </div>
                                <div class="flex justify-between items-end">
                                    <div>
                                        <p class="text-[10px] text-slate-500 uppercase">TOTAL TAGIHAN:</p>
                                        <p class="text-sm font-bold">RP <?= number_format((int)$do['total_price'], 0, ',', '.') ?></p>
                                    </div>
                                    <span class="bg-slate-200 text-[#181c28] text-[10px] font-bold px-2 py-0.5 uppercase">
                                        SELESAI &amp; DIAMBIL
                                    </span>
                                </div>

                                <!-- Digital Receipt Box -->
                                <div class="bg-[#f8f9fa] border border-slate-300 p-3 text-[11px] space-y-2">
                                    <div class="text-center border-b border-dashed border-slate-400 pb-2">
                                        <p class="font-bold tracking-wider">BUKTI RESI DIGITAL RESMI</p>
                                        <p class="text-[10px] text-slate-500">YARA YARA LAUNDRY SYSTEM</p>
                                    </div>
                                    <div class="space-y-1 text-[10px]">
                                        <div class="flex justify-between">
                                            <span class="text-slate-500">NO. TRANSAKSI</span>
                                            <span class="font-bold"><?= htmlspecialchars($trxNo) ?></span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-slate-500">WAKTU SELESAI</span>
                                            <span><?= date('d/m/Y H:i', strtotime($do['updated_at'])) ?> WIB</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-slate-500">ITEM</span>
                                            <span><?= htmlspecialchars($do['service_name']) ?> (<?= $dQty ?>)</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-slate-500">METODE BAYAR</span>
                                            <span class="font-bold"><?= htmlspecialchars($payMethod) ?></span>
                                        </div>
                                    </div>
                                    <div class="flex justify-between items-center border-t border-dashed border-slate-400 pt-2 font-bold text-xs">
                                        <span>TOTAL PEMBAYARAN</span>
                                        <span>RP <?= number_format((int)$do['total_price'], 0, ',', '.') ?></span>
                                    </div>

                                    <div class="grid grid-cols-2 gap-2 pt-1">
                                        <button onclick="window.print()" class="bg-[#181c28] text-white text-[11px] font-bold py-2 flex items-center justify-center gap-1.5 hover:bg-slate-800 transition-colors" type="button">
                                            <span>[CETAK RESI]</span>
                                        </button>
                                        <a href="pesan.php" class="bg-white border border-[#181c28] text-[#181c28] text-[11px] font-bold py-2 text-center hover:bg-slate-100 transition-colors">
                                            [PESAN LAGI]
                                        </a>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </section>
                <!-- END: CompletedHistorySection -->
            <?php endif; ?>
        </main>
        <!-- END: MainContent -->

        <?php require __DIR__ . '/includes/bottom-nav.php'; ?>
    </div>

    <script data-purpose="filter-tabs-interaction">
        document.addEventListener('DOMContentLoaded', () => {
            const tabs = document.querySelectorAll('.filter-tab');
            const activeSection = document.querySelector('[data-status="active"]');
            const doneSection = document.querySelector('[data-status="done"]');
            const searchInput = document.getElementById('search-archive');

            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    tabs.forEach(t => {
                        t.classList.remove('bg-[#181c28]', 'text-white', 'border-[#181c28]');
                        t.classList.add('bg-white', 'text-[#181c28]', 'border-slate-300');
                        t.setAttribute('aria-selected', 'false');
                    });

                    tab.classList.remove('bg-white', 'text-[#181c28]', 'border-slate-300');
                    tab.classList.add('bg-[#181c28]', 'text-white', 'border-[#181c28]');
                    tab.setAttribute('aria-selected', 'true');

                    const filter = tab.getAttribute('data-filter');
                    if (activeSection) activeSection.classList.toggle('hidden', filter === 'done');
                    if (doneSection) doneSection.classList.toggle('hidden', filter === 'active');
                });
            });

            if (searchInput) {
                searchInput.addEventListener('input', (e) => {
                    const q = e.target.value.toLowerCase().trim();
                    document.querySelectorAll('.order-searchable').forEach(item => {
                        item.classList.toggle('hidden', q !== '' && !item.textContent.toLowerCase().includes(q));
                    });
                });
            }
        });
    </script>
</body>

</html>