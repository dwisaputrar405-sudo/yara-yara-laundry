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
            background-color: #EEF4FA;
            color: #0F2854;
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

        #print-area {
            display: none;
        }

        @media print {
            @page {
                margin: 12mm;
            }
            html, body {
                height: auto !important;
                min-height: 0 !important;
                background: #ffffff !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            #screen-wrapper {
                display: none !important;
            }
            #print-area {
                display: block !important;
                width: 340px !important;
                max-width: 100% !important;
                margin: 0 auto !important;
                background: #ffffff !important;
                border: 2px solid #0F2854 !important;
                padding: 16px !important;
            }
            #print-area .no-print {
                display: none !important;
            }
        }
    </style>
</head>

<body class="min-h-screen pb-24">
    <!-- Wadah khusus cetak 1 halaman bersih -->
    <div id="print-area" class="text-[11px] space-y-2"></div>

    <div id="screen-wrapper" class="max-w-md mx-auto min-h-screen bg-[#EEF4FA] border-x border-[#DCEAF7] flex flex-col">
        <?php require __DIR__ . '/includes/header.php'; ?>

        <!-- BEGIN: MainContent -->
        <main class="flex-1 px-4 py-5 space-y-6">
            <!-- BEGIN: PageTitle -->
            <section class="bg-[#F8FBFF] border border-[#C9D9EA] p-4" data-purpose="page-header">
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
                        PENCARIAN ARSIP (KODE / TANGGAL / LAYANAN)
                    </label>
                    <div class="relative flex items-center bg-[#F8FBFF] border border-[#B8CDE2] focus-within:border-[#0F2854]">
                        <span class="pl-3 text-slate-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </span>
                        <input class="w-full border-0 bg-transparent py-2.5 px-2.5 text-xs focus:ring-0 placeholder:text-slate-400" id="search-archive" placeholder="CARI KODE / TANGGAL" type="text">
                        <span class="pr-3 text-[10px] text-slate-400 font-semibold">ENTER</span>
                    </div>
                </div>

                <!-- Filter Tabs -->
                <div aria-label="Filter Status Pesanan" class="flex items-center gap-2 overflow-x-auto no-scrollbar" role="tablist">
                    <button aria-selected="true" class="filter-tab shrink-0 px-3 py-1.5 text-xs font-bold border border-[#0F2854] bg-[#0F2854] text-white" data-filter="all" role="tab" type="button">
                        SEMUA (<?= $totalCount ?>)
                    </button>
                    <button aria-selected="false" class="filter-tab shrink-0 px-3 py-1.5 text-xs font-bold border border-[#C9D9EA] bg-[#F8FBFF] text-[#0F2854] hover:border-[#1C4D8D]" data-filter="active" role="tab" type="button">
                        BERJALAN (<?= $activeCount ?>)
                    </button>
                    <button aria-selected="false" class="filter-tab shrink-0 px-3 py-1.5 text-xs font-bold border border-[#C9D9EA] bg-[#F8FBFF] text-[#0F2854] hover:border-[#1C4D8D]" data-filter="done" role="tab" type="button">
                        SELESAI (<?= $doneCount ?>)
                    </button>
                </div>
            </section>
            <!-- END: SearchAndFilter -->

            <?php if ($totalCount === 0): ?>
                <section class="bg-[#F8FBFF] border border-[#C9D9EA] p-6 text-center space-y-3">
                    <div class="text-xs font-bold text-[#0F2854]">BELUM ADA RIWAYAT TRANSAKSI</div>
                    <p class="text-[11px] text-slate-500">Kamu belum pernah membuat pesanan laundry di akun ini.</p>
                    <a href="pesan.php" class="inline-block bg-[#0F2854] text-white px-4 py-2 text-xs font-bold">
                        + BUAT PESANAN PERTAMA
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
                        <section class="order-searchable bg-[#F8FBFF] border-2 border-[#0F2854] p-4 space-y-3" data-purpose="active-order-card">
                            <div class="flex items-center justify-between border-b border-dashed border-[#C9D9EA] pb-2.5">
                                <div class="flex items-center gap-1.5 text-xs font-bold">
                                    <span class="inline-block w-2.5 h-2.5 bg-[#0F2854]"></span>
                                    <span>PESANAN SEDANG DIPROSES</span>
                                </div>
                                <span class="bg-[#DCEAF7] text-[#0F2854] text-[10px] font-bold px-2 py-0.5 uppercase">
                                    LIVE TRACKING
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

                            <div class="border-t border-dashed border-[#C9D9EA] pt-2.5">
                                <p class="text-[10px] text-slate-500 uppercase">LAYANAN &amp; MUATAN:</p>
                                <p class="text-sm font-bold"><?= htmlspecialchars($ao['service_name']) ?> (<?= $qtyText ?><?= (int)$ao['is_estimated_weight'] === 1 ? ' - Est.' : '' ?>)</p>
                            </div>

                            <div class="bg-[#EAF2FA] border border-[#C9D9EA] p-2.5 space-y-1.5">
                                <div class="flex justify-between items-center text-[10px] font-bold">
                                    <span>STATUS TAHAPAN:</span>
                                    <span>(TAHAP <?= $displayStep ?> DARI 7)</span>
                                </div>
                                <div class="w-full h-2 bg-[#C9D9EA]">
                                    <div class="h-full bg-[#4988C4]" style="width: <?= $pct ?>%;"></div>
                                </div>
                                <div class="flex justify-between items-center text-[10px] text-slate-600 pt-0.5 gap-2">
                                    <span class="font-bold text-[#0F2854]"><?= $stageNames[$step] ?? 'DIPROSES' ?></span>
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
                                    <span class="bg-[#DCEAF7] text-[#0F2854] text-[10px] font-bold px-2 py-0.5">
                                        <?= htmlspecialchars($payLabel) ?>
                                    </span>
                                </div>
                            </div>

                            <a href="lacak.php?code=<?= urlencode($ao['receipt_no']) ?>" class="block text-center w-full bg-[#0F2854] text-white text-xs font-bold py-2.5 tracking-wider uppercase hover:bg-[#1C4D8D] transition-colors">
                                LACAK PROSES SEKARANG →
                            </a>
                        </section>
                    <?php endforeach; ?>
                </div>
                <!-- END: ActiveOrderSection -->

                <!-- BEGIN: CompletedHistorySection -->
                <section class="space-y-4" data-purpose="completed-orders-list" data-status="done">
                    <div class="flex items-center justify-between border-b border-[#C9D9EA] pb-2">
                        <div class="flex items-center gap-1.5 text-xs font-bold uppercase">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                            <span>ARSIP TRANSAKSI SELESAI</span>
                        </div>
                        <span class="text-[10px] text-slate-500 font-semibold"><?= $doneCount ?> DOKUMEN</span>
                    </div>

                    <?php if ($doneCount === 0): ?>
                        <div class="bg-[#F8FBFF] border border-[#C9D9EA] p-4 text-center text-xs text-slate-500">
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
                            <article id="<?= htmlspecialchars($cleanId) ?>" class="order-searchable bg-[#F8FBFF] border <?= $idx === 0 ? 'border-[#B8CDE2]' : 'border-[#C9D9EA]' ?> p-4 space-y-3" data-purpose="digital-receipt-card">
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
                                    <p class="text-[10px] text-slate-500">KODE LAYANAN: <?= htmlspecialchars($do['service_code']) ?></p>
                                </div>
                                <div class="flex justify-between items-end">
                                    <div>
                                        <p class="text-[10px] text-slate-500 uppercase">TOTAL TAGIHAN:</p>
                                        <p class="text-sm font-bold">RP <?= number_format((int)$do['total_price'], 0, ',', '.') ?></p>
                                    </div>
                                    <span class="bg-[#DCEAF7] text-[#0F2854] text-[10px] font-bold px-2 py-0.5 uppercase">
                                        SELESAI &amp; DIAMBIL
                                    </span>
                                </div>

                                <!-- Digital Receipt Box -->
                                <div class="receipt-box bg-[#F8FBFF] border border-[#C9D9EA] p-3 text-[11px] space-y-2">
                                    <div class="text-center border-b border-dashed border-[#B8CDE2] pb-2">
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
                                    <div class="flex justify-between items-center border-t border-dashed border-[#B8CDE2] pt-2 font-bold text-xs">
                                        <span>TOTAL PEMBAYARAN</span>
                                        <span>RP <?= number_format((int)$do['total_price'], 0, ',', '.') ?></span>
                                    </div>

                                    <div class="no-print grid grid-cols-2 gap-2 pt-1">
                                        <button onclick="printResi(this)" class="bg-[#0F2854] text-white text-[11px] font-bold py-2 flex items-center justify-center gap-1.5 hover:bg-[#1C4D8D] transition-colors" type="button">
                                            <span>CETAK RESI</span>
                                        </button>
                                        <a href="pesan.php" class="bg-[#F8FBFF] border border-[#0F2854] text-[#0F2854] text-[11px] font-bold py-2 text-center hover:bg-[#EAF2FA] transition-colors">
                                            PESAN LAGI
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
        function printResi(btn) {
            const targetBox = btn.closest('.receipt-box');
            const printArea = document.getElementById('print-area');
            if (targetBox && printArea) {
                printArea.innerHTML = targetBox.innerHTML;
                window.print();
            }
        }

        window.addEventListener('afterprint', () => {
            const printArea = document.getElementById('print-area');
            if (printArea) printArea.innerHTML = '';
        });

        document.addEventListener('DOMContentLoaded', () => {
            const tabs = document.querySelectorAll('.filter-tab');
            const activeSection = document.querySelector('[data-status="active"]');
            const doneSection = document.querySelector('[data-status="done"]');
            const searchInput = document.getElementById('search-archive');

            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    tabs.forEach(t => {
                        t.classList.remove('bg-[#0F2854]', 'text-white', 'border-[#0F2854]');
                        t.classList.add('bg-[#F8FBFF]', 'text-[#0F2854]', 'border-[#C9D9EA]');
                        t.setAttribute('aria-selected', 'false');
                    });

                    tab.classList.remove('bg-[#F8FBFF]', 'text-[#0F2854]', 'border-[#C9D9EA]');
                    tab.classList.add('bg-[#0F2854]', 'text-white', 'border-[#0F2854]');
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