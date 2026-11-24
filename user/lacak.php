<?php
$activeNav = 'lacak';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
    <title>Lacak Pesanan Langsung - #YYL-8921</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet">
    <style data-purpose="typography">
        body {
            font-family: 'JetBrains Mono', ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            background-color: #f4f5f7;
            color: #181c28;
        }
    </style>
    <style data-purpose="timeline-connector">
        .timeline-line {
            position: absolute;
            left: 11px;
            top: 24px;
            bottom: 12px;
            width: 2px;
            background-color: #cbd5e1;
            z-index: 0;
        }

        .timeline-line-active {
            position: absolute;
            left: 11px;
            top: 24px;
            height: 88px;
            width: 2px;
            background-color: #181c28;
            z-index: 1;
        }
    </style>
</head>

<body class="min-h-screen pb-24 bg-[#f4f5f7]">
    <div class="max-w-md mx-auto min-h-screen bg-[#f4f5f7] border-x border-slate-200 flex flex-col">
        <?php require __DIR__ . '/includes/header.php'; ?>

        <!-- BEGIN: MainContent -->
        <main class="px-4 py-4 space-y-3 flex-1">
            <!-- BEGIN: TrackingNumberCard -->
            <section class="bg-white border border-slate-200 p-3.5 flex items-center justify-between" data-purpose="tracking-header">
                <div>
                    <p class="text-[10px] tracking-wider text-slate-500 uppercase">[NO. PELACAKAN SISTEM]</p>
                    <h1 class="text-lg font-extrabold tracking-tight text-[#181c28] mt-0.5">#YYL-8921</h1>
                </div>
                <button id="btn-reload-track" class="bg-[#eef2f9] hover:bg-slate-200 border border-slate-300 px-3 py-2 text-[11px] font-bold text-[#181c28] flex items-center gap-1.5 transition-colors" type="button">
                    <svg id="reload-icon" class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" stroke-linecap="square" stroke-linejoin="miter"></path>
                    </svg>
                    <span id="reload-label">[MUAT ULANG]</span>
                </button>
            </section>
            <!-- END: TrackingNumberCard -->

            <!-- BEGIN: ActiveStatusProgress -->
            <section class="bg-white border border-slate-200 p-3.5 space-y-3" data-purpose="active-status">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] tracking-wider text-slate-600 font-medium">[STATUS AKTIF]</span>
                    <span class="bg-[#181c28] text-white text-[11px] font-bold px-2.5 py-1 tracking-wider">
                        SEDANG DICUCI
                    </span>
                </div>
                <div class="bg-[#eef2f9] p-3 space-y-2.5">
                    <div class="flex items-start justify-between gap-2 text-[11px]">
                        <div class="flex flex-col">
                            <span class="text-[10px] text-slate-500 uppercase tracking-wider">ESTIMASI SELESAI</span>
                            <span class="text-xs font-semibold text-[#181c28]">Rabu, 24 Okt 2024</span>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-extrabold text-[#181c28]">17:00 WIB</span>
                            <p class="text-[10px] text-slate-500">Tepat Waktu</p>
                        </div>
                    </div>
                    <div class="w-full h-2.5 bg-slate-300 overflow-hidden">
                        <div class="h-full bg-[#181c28]" style="width: 42.8%;"></div>
                    </div>
                    <div class="flex items-center justify-between text-[10px] font-bold">
                        <span class="text-slate-600">TAHAP 3 DARI 7</span>
                        <span class="text-[#181c28]">[42.8% SELESAI]</span>
                    </div>
                </div>
            </section>
            <!-- END: ActiveStatusProgress -->

            <!-- BEGIN: CompactTimeline -->
            <section class="bg-white border border-slate-200 p-3.5" data-purpose="order-timeline">
                <h2 class="text-[11px] font-bold tracking-wider text-[#181c28] mb-3 uppercase">
                    [DIAGRAM ALUR PROSES]
                </h2>
                <div class="relative space-y-2.5">
                    <div aria-hidden="true" class="timeline-line"></div>
                    <div aria-hidden="true" class="timeline-line-active"></div>

                    <div class="relative z-10 flex items-center gap-3">
                        <div class="w-6 h-6 bg-[#181c28] text-white text-[11px] font-bold flex items-center justify-center shrink-0">[x]</div>
                        <div class="flex-1 flex items-center justify-between gap-2">
                            <div>
                                <p class="text-xs font-bold text-[#181c28]">1. DITERIMA</p>
                                <p class="text-[10px] text-slate-500">23 Okt 09:15 WIB</p>
                            </div>
                            <span class="bg-[#eef2f9] text-[#181c28] text-[10px] font-semibold px-2 py-0.5">[SELESAI]</span>
                        </div>
                    </div>

                    <div class="relative z-10 flex items-center gap-3">
                        <div class="w-6 h-6 bg-[#181c28] text-white text-[11px] font-bold flex items-center justify-center shrink-0">[x]</div>
                        <div class="flex-1 flex items-center justify-between gap-2">
                            <div>
                                <p class="text-xs font-bold text-[#181c28]">2. DITIMBANG</p>
                                <p class="text-[10px] text-slate-500">23 Okt 09:30 WIB</p>
                            </div>
                            <span class="bg-[#eef2f9] text-[#181c28] text-[10px] font-semibold px-2 py-0.5">[SELESAI]</span>
                        </div>
                    </div>

                    <div class="relative z-10 flex items-start gap-3 bg-[#eef2f9] p-2 -mx-2 border border-slate-300">
                        <div class="w-6 h-6 bg-white border border-[#181c28] text-[#181c28] text-[11px] font-bold flex items-center justify-center shrink-0 mt-0.5">[/]</div>
                        <div class="flex-1 space-y-1">
                            <div class="flex items-center justify-between gap-2">
                                <p class="text-xs font-extrabold text-[#181c28]">3. DICUCI</p>
                                <span class="bg-[#181c28] text-white text-[10px] font-bold px-2 py-0.5">[BERJALAN]</span>
                            </div>
                            <div class="flex items-center justify-between text-[10px] text-[#181c28] font-medium">
                                <span>Mulai: 23 Okt 11:00 WIB</span>
                                <span class="bg-white px-1.5 py-0.5 border border-slate-300 font-bold">~18 Menit</span>
                            </div>
                        </div>
                    </div>

                    <div class="relative z-10 flex items-center gap-3">
                        <div class="w-6 h-6 bg-slate-100 border border-slate-300 text-slate-400 text-[11px] font-bold flex items-center justify-center shrink-0">[ ]</div>
                        <div class="flex-1 flex items-center justify-between gap-2">
                            <div>
                                <p class="text-xs font-bold text-slate-600">4. DIKERINGKAN</p>
                                <p class="text-[10px] text-slate-400">Est. 23 Okt 13:00 WIB</p>
                            </div>
                            <span class="text-slate-400 text-[10px] font-medium">[ANTREAN]</span>
                        </div>
                    </div>

                    <div class="relative z-10 flex items-center gap-3">
                        <div class="w-6 h-6 bg-slate-100 border border-slate-300 text-slate-400 text-[11px] font-bold flex items-center justify-center shrink-0">[ ]</div>
                        <div class="flex-1 flex items-center justify-between gap-2">
                            <div>
                                <p class="text-xs font-bold text-slate-600">5. DISETRIKA</p>
                                <p class="text-[10px] text-slate-400">Est. 23 Okt 14:30 WIB</p>
                            </div>
                            <span class="text-slate-400 text-[10px] font-medium">[ANTREAN]</span>
                        </div>
                    </div>

                    <div class="relative z-10 flex items-center gap-3">
                        <div class="w-6 h-6 bg-slate-100 border border-slate-300 text-slate-400 text-[11px] font-bold flex items-center justify-center shrink-0">[ ]</div>
                        <div class="flex-1 flex items-center justify-between gap-2">
                            <div>
                                <p class="text-xs font-bold text-slate-600">6. SIAP DIAMBIL</p>
                                <p class="text-[10px] text-slate-400">Est. 23 Okt 16:30 WIB</p>
                            </div>
                            <span class="text-slate-400 text-[10px] font-medium">[ANTREAN]</span>
                        </div>
                    </div>

                    <div class="relative z-10 flex items-center gap-3">
                        <div class="w-6 h-6 bg-slate-100 border border-slate-300 text-slate-400 text-[11px] font-bold flex items-center justify-center shrink-0">[ ]</div>
                        <div class="flex-1 flex items-center justify-between gap-2">
                            <div>
                                <p class="text-xs font-bold text-slate-600">7. SELESAI</p>
                                <p class="text-[10px] text-slate-400">Est. 23 Okt 17:00 WIB</p>
                            </div>
                            <span class="text-slate-400 text-[10px] font-medium">[ANTREAN]</span>
                        </div>
                    </div>
                </div>
            </section>
            <!-- END: CompactTimeline -->

            <!-- BEGIN: BillDetails -->
            <section class="bg-white border border-slate-200 p-3.5 space-y-2.5" data-purpose="bill-summary">
                <div class="flex items-center justify-between">
                    <h2 class="text-[11px] font-bold tracking-wider text-[#181c28] uppercase">[RINCIAN TAGIHAN]</h2>
                    <span class="text-[10px] text-slate-500">GERAI UTAMA</span>
                </div>
                <div class="bg-[#eef2f9] p-3 space-y-2 text-[11px]">
                    <div class="space-y-1.5 text-xs">
                        <div class="flex justify-between items-center py-0.5 min-w-0">
                            <span class="text-slate-500 text-[11px]">LAYANAN</span>
                            <span class="font-bold text-[#181c28] text-right text-[11px]">CUCI KOMPLIT (2 HARI)</span>
                        </div>
                        <div class="flex justify-between items-center py-0.5 min-w-0">
                            <span class="text-slate-500 text-[11px]">BEBAN</span>
                            <span class="font-medium text-[#181c28] text-right text-[11px]">4.8 KG × RP 8.000</span>
                        </div>
                        <div class="flex justify-between items-center py-0.5 min-w-0">
                            <span class="text-slate-500 text-[11px]">METODE</span>
                            <span class="font-bold text-[#181c28] text-right text-[11px]">DIAMBIL SENDIRI</span>
                        </div>
                        <div class="flex justify-between items-center py-0.5 min-w-0">
                            <span class="text-slate-500 text-[11px]">STATUS</span>
                            <span class="bg-white border border-slate-300 px-2 py-0.5 font-bold text-[#181c28] text-[10px]">[LUNAS - QRIS]</span>
                        </div>
                    </div>
                    <div class="border-t border-slate-300 pt-2.5 mt-2 flex justify-between items-center">
                        <span class="font-bold text-xs text-[#181c28]">TOTAL TAGIHAN</span>
                        <span class="text-sm font-extrabold text-[#181c28]">RP 38.400</span>
                    </div>
                </div>
            </section>
            <!-- END: BillDetails -->

            <!-- BEGIN: ActionButtons -->
            <section class="grid grid-cols-2 gap-2.5" data-purpose="action-buttons">
                <a href="riwayat.php" class="bg-white hover:bg-slate-50 border border-slate-300 py-2.5 px-3 text-[11px] font-bold text-[#181c28] flex items-center justify-center gap-1.5 transition-colors">
                    [RESI DIGITAL]
                </a>
                <a href="https://wa.me/628110000000?text=Halo%20Admin%20Yara%20Laundry,%20saya%20ingin%20menanyakan%20pesanan%20%23YYL-8921" target="_blank" rel="noopener noreferrer" class="bg-[#181c28] hover:bg-slate-800 text-white py-2.5 px-3 text-[11px] font-bold flex items-center justify-center gap-1.5 transition-colors">
                    [ADMIN KASIR]
                </a>
            </section>
            <!-- END: ActionButtons -->
        </main>
        <!-- END: MainContent -->

        <?php require __DIR__ . '/includes/bottom-nav.php'; ?>
    </div>

    <script>
        document.getElementById('btn-reload-track').addEventListener('click', function () {
            const label = document.getElementById('reload-label');
            const icon = document.getElementById('reload-icon');
            label.textContent = '[MEMUAT...]';
            icon.classList.add('animate-spin');
            setTimeout(() => {
                label.textContent = '[TERBARU]';
                icon.classList.remove('animate-spin');
                setTimeout(() => { label.textContent = '[MUAT ULANG]'; }, 1200);
            }, 450);
        });
    </script>
</body>

</html>