<?php
require __DIR__ . '/includes/guard.php';
$activeNav = 'riwayat';
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
                        PENCARIAN ARSIP [KODE / TANGGAL]
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
                        [SEMUA (14)]
                    </button>
                    <button aria-selected="false" class="filter-tab shrink-0 px-3 py-1.5 text-xs font-bold border border-slate-300 bg-white text-[#181c28] hover:border-[#181c28]" data-filter="active" role="tab" type="button">
                        [BERJALAN (1)]
                    </button>
                    <button aria-selected="false" class="filter-tab shrink-0 px-3 py-1.5 text-xs font-bold border border-slate-300 bg-white text-[#181c28] hover:border-[#181c28]" data-filter="done" role="tab" type="button">
                        [SELESAI (11)]
                    </button>
                </div>
            </section>
            <!-- END: SearchAndFilter -->

            <!-- BEGIN: ActiveOrderSection -->
            <section class="order-searchable bg-white border-2 border-[#181c28] p-4 space-y-3" data-purpose="active-order-card" data-status="active">
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
                        <p class="text-sm font-bold">#YYL-8921</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] text-slate-500 uppercase">TANGGAL MASUK:</p>
                        <p class="font-semibold">23 Okt 2024, 09:15</p>
                    </div>
                </div>

                <div class="border-t border-dashed border-slate-300 pt-2.5">
                    <p class="text-[10px] text-slate-500 uppercase">LAYANAN &amp; MUATAN:</p>
                    <p class="text-sm font-bold">Cuci Komplit Reguler (4.8 Kg)</p>
                </div>

                <div class="bg-slate-100 border border-slate-300 p-2.5 space-y-1.5">
                    <div class="flex justify-between items-center text-[10px] font-bold">
                        <span>STATUS TAHAPAN:</span>
                        <span>[TAHAP 3 DARI 7]</span>
                    </div>
                    <div class="w-full h-2 bg-slate-300">
                        <div class="h-full bg-[#181c28]" style="width: 43%;"></div>
                    </div>
                    <div class="flex justify-between items-center text-[10px] text-slate-600 pt-0.5">
                        <span>SEDANG DICUCI</span>
                        <span>ETA: HARI INI 17:00</span>
                    </div>
                </div>

                <div class="flex justify-between items-end pt-1">
                    <div>
                        <p class="text-[10px] text-slate-500 uppercase">TOTAL BIAYA:</p>
                        <p class="text-sm font-bold">RP 38.400</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] text-slate-500 uppercase mb-0.5">STATUS BAYAR:</p>
                        <span class="bg-slate-200 text-[#181c28] text-[10px] font-bold px-2 py-0.5">
                            [LUNAS - QRIS]
                        </span>
                    </div>
                </div>

                <a href="lacak.php" class="block text-center w-full bg-[#181c28] text-white text-xs font-bold py-2.5 tracking-wider uppercase hover:bg-slate-800 transition-colors">
                    LACAK PROSES SEKARANG →
                </a>
            </section>
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
                    <span class="text-[10px] text-slate-500 font-semibold">[11 DOKUMEN]</span>
                </div>

                <!-- BEGIN: DigitalReceiptCard -->
                <article id="YYL-8804" class="order-searchable bg-white border border-slate-400 p-4 space-y-3" data-purpose="digital-receipt-card">
                    <div class="flex justify-between items-start text-xs">
                        <div>
                            <p class="text-[10px] text-slate-500 uppercase">KODE:</p>
                            <p class="text-sm font-bold">#YYL-8804</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] text-slate-500 uppercase">TGL SELESAI:</p>
                            <p class="font-semibold">18 Okt 2024</p>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm font-bold">Cuci Bedcover Besar (1 Pcs)</p>
                        <p class="text-[10px] text-slate-500">[PAKET KHUSUS - HYGIENIC WASH]</p>
                    </div>
                    <div class="flex justify-between items-end">
                        <div>
                            <p class="text-[10px] text-slate-500 uppercase">TOTAL TAGIHAN:</p>
                            <p class="text-sm font-bold">RP 35.000</p>
                        </div>
                        <span class="bg-slate-200 text-[#181c28] text-[10px] font-bold px-2 py-0.5 uppercase">
                            SELESAI &amp; DIAMBIL
                        </span>
                    </div>

                    <!-- Simplified Digital Receipt Box -->
                    <div class="bg-[#f8f9fa] border border-slate-300 p-3 text-[11px] space-y-2">
                        <div class="text-center border-b border-dashed border-slate-400 pb-2">
                            <p class="font-bold tracking-wider">BUKTI RESI DIGITAL RESMI</p>
                            <p class="text-[10px] text-slate-500">YARA YARA LAUNDRY SYSTEM</p>
                        </div>
                        <div class="space-y-1 text-[10px]">
                            <div class="flex justify-between">
                                <span class="text-slate-500">NO. TRANSAKSI</span>
                                <span class="font-bold">REC-20241018-8804</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">WAKTU AMBIL</span>
                                <span>18/10/2024 16:42 WIB</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">ITEM</span>
                                <span>1x Bedcover King</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">METODE BAYAR</span>
                                <span class="font-bold">TUNAI OUTLET</span>
                            </div>
                        </div>
                        <div class="flex justify-between items-center border-t border-dashed border-slate-400 pt-2 font-bold text-xs">
                            <span>TOTAL PEMBAYARAN</span>
                            <span>RP 35.000</span>
                        </div>

                        <button onclick="window.print()" class="w-full mt-2 bg-[#181c28] text-white text-[11px] font-bold py-2 flex items-center justify-center gap-1.5 hover:bg-slate-800 transition-colors" type="button">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                            <span>[UNDUH PDF]</span>
                        </button>
                    </div>
                </article>
                <!-- END: DigitalReceiptCard -->

                <!-- Completed Order Item 2 -->
                <article id="YYL-8712" class="order-searchable bg-white border border-slate-300 p-4 space-y-2.5" data-purpose="completed-order-item">
                    <div class="flex justify-between items-start text-xs">
                        <div>
                            <p class="text-[10px] text-slate-500 uppercase">KODE:</p>
                            <p class="text-sm font-bold">#YYL-8712</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] text-slate-500 uppercase">TGL SELESAI:</p>
                            <p class="font-semibold">12 Okt 2024</p>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm font-bold">Setrika Saja (3.2 Kg)</p>
                        <p class="text-[10px] text-slate-500">[LAYANAN REGULER 2 HARI]</p>
                    </div>
                    <div class="flex justify-between items-center pt-1">
                        <div>
                            <p class="text-[10px] text-slate-500 uppercase">TOTAL BIAYA:</p>
                            <p class="text-sm font-bold">RP 19.200</p>
                        </div>
                        <a href="pesan.php" class="bg-[#181c28] text-white text-[11px] font-bold px-3 py-1.5 hover:bg-slate-800 transition-colors">
                            [PESAN LAGI]
                        </a>
                    </div>
                </article>

                <!-- Completed Order Item 3 -->
                <article id="YYL-8650" class="order-searchable bg-white border border-slate-300 p-4 space-y-2.5" data-purpose="completed-order-item">
                    <div class="flex justify-between items-start text-xs">
                        <div>
                            <p class="text-[10px] text-slate-500 uppercase">KODE:</p>
                            <p class="text-sm font-bold">#YYL-8650</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] text-slate-500 uppercase">TANGGAL:</p>
                            <p class="font-semibold">28 Sep 2024</p>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm font-bold">Cuci Kering Saja (5.0 Kg)</p>
                        <p class="text-[10px] text-slate-500">[LAYANAN REGULER WASH &amp; FOLD]</p>
                    </div>
                    <div class="flex justify-between items-end pt-1">
                        <div>
                            <p class="text-[10px] text-slate-500 uppercase">TOTAL:</p>
                            <p class="text-sm font-bold">RP 30.000</p>
                        </div>
                        <span class="bg-slate-200 text-[#181c28] text-[10px] font-bold px-2 py-0.5 uppercase">
                            SELESAI &amp; DIAMBIL
                        </span>
                    </div>
                </article>
            </section>
            <!-- END: CompletedHistorySection -->
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
                    activeSection.classList.toggle('hidden', filter === 'done');
                    doneSection.classList.toggle('hidden', filter === 'active');
                });
            });

            searchInput.addEventListener('input', (e) => {
                const q = e.target.value.toLowerCase().trim();
                document.querySelectorAll('.order-searchable').forEach(item => {
                    item.classList.toggle('hidden', q !== '' && !item.textContent.toLowerCase().includes(q));
                });
            });
        });
    </script>
</body>

</html>