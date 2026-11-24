<?php
$activeNav = 'beranda';
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

        <!-- BEGIN: MainContent -->
        <main class="flex-1 px-4 pt-5 space-y-5" data-purpose="main-dashboard">
            <!-- BEGIN: GreetingSection -->
            <section class="bg-white border border-slate-200 p-4" data-purpose="greeting-card">
                <h1 class="text-xl font-bold tracking-tight text-[#181c28]">
                    Halo, Budi Santoso
                </h1>
            </section>
            <!-- END: GreetingSection -->

            <!-- BEGIN: PrimaryAction -->
            <section data-purpose="create-order-action">
                <a href="pesan.php" class="w-full bg-[#181c28] hover:bg-slate-800 active:bg-slate-900 text-white py-3.5 px-4 flex items-center justify-center gap-2.5 text-xs font-bold tracking-wider uppercase transition-colors">
                    <span class="inline-flex items-center justify-center w-4 h-4 border border-white text-[11px] leading-none">+</span>
                    <span>[+ BUAT PESANAN LAUNDRY BARU]</span>
                </a>
            </section>
            <!-- END: PrimaryAction -->

            <!-- BEGIN: ActiveOrderSection -->
            <section class="bg-white border border-slate-200 p-4 space-y-4" data-purpose="active-order-card">
                <div class="bg-[#eef2f9] px-3 py-1.5 flex items-center justify-between text-[11px]">
                    <span class="font-bold tracking-wider text-[#181c28]">PESANAN AKTIF (1)</span>
                    <span class="text-slate-500">[LIVE TRACKING]</span>
                </div>

                <div class="flex items-start justify-between gap-2">
                    <div class="text-xl font-bold tracking-tight text-[#181c28] leading-tight">
                        #YYL-8921
                    </div>
                    <div class="bg-[#eef2f9] px-2 py-1 text-[10px] font-bold text-[#181c28] text-right leading-snug">
                        [SEDANG DICUCI - TAHAP 3 DARI 7]
                    </div>
                </div>

                <div class="space-y-2.5 text-xs">
                    <div>
                        <div class="text-[10px] text-slate-500 uppercase tracking-wider">LAYANAN:</div>
                        <div class="font-bold text-[#181c28] text-sm">Cuci Komplit Reguler (4.8 Kg)</div>
                    </div>
                    <div>
                        <div class="text-[10px] text-slate-500 uppercase tracking-wider">ESTIMASI SELESAI:</div>
                        <div class="text-[#181c28]">Rabu, 24 Okt 2024 - 17:00 WIB</div>
                    </div>
                </div>

                <!-- Progress Bar 7 Tahap -->
                <div class="bg-[#f4f5f7] p-3 border border-slate-200" data-purpose="workflow-progress">
                    <div class="text-[10px] text-slate-500 mb-2">[PROGRES_WORKFLOW_TAHAP]</div>
                    <div class="grid grid-cols-7 gap-1 text-center">
                        <div>
                            <div class="h-1.5 bg-[#181c28] mb-1"></div>
                            <span class="text-[9px] font-medium text-[#181c28] block truncate">Terima</span>
                        </div>
                        <div>
                            <div class="h-1.5 bg-[#181c28] mb-1"></div>
                            <span class="text-[9px] font-medium text-[#181c28] block truncate">Timbang</span>
                        </div>
                        <div>
                            <div class="h-1.5 bg-[#181c28] mb-1"></div>
                            <span class="text-[9px] font-bold text-[#181c28] block truncate">[Cuci]</span>
                        </div>
                        <div>
                            <div class="h-1.5 bg-slate-300 mb-1"></div>
                            <span class="text-[9px] text-slate-400 block truncate">Kering</span>
                        </div>
                        <div>
                            <div class="h-1.5 bg-slate-300 mb-1"></div>
                            <span class="text-[9px] text-slate-400 block truncate">Setrika</span>
                        </div>
                        <div>
                            <div class="h-1.5 bg-slate-300 mb-1"></div>
                            <span class="text-[9px] text-slate-400 block truncate">Siap</span>
                        </div>
                        <div>
                            <div class="h-1.5 bg-slate-300 mb-1"></div>
                            <span class="text-[9px] text-slate-400 block truncate">Selesai</span>
                        </div>
                    </div>
                </div>

                <a href="lacak.php" class="w-full bg-[#eef2f9] hover:bg-slate-200 text-[#181c28] py-2.5 px-3 flex items-center justify-between text-xs font-bold tracking-wider transition-colors">
                    <span>[LACAK STATUS PESANAN]</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="9"></circle>
                        <circle cx="12" cy="12" r="4"></circle>
                        <circle cx="12" cy="12" fill="currentColor" r="1"></circle>
                    </svg>
                </a>
            </section>
            <!-- END: ActiveOrderSection -->

            <!-- BEGIN: RecentHistorySection -->
            <section class="space-y-3" data-purpose="recent-history">
                <div class="flex items-center justify-between">
                    <h2 class="text-xs font-bold tracking-wider text-[#181c28] uppercase">
                        RIWAYAT PESANAN TERAKHIR
                    </h2>
                    <a class="text-[11px] text-slate-600 underline hover:text-[#181c28]" href="riwayat.php">
                        [LIHAT SEMUA]
                    </a>
                </div>

                <article class="bg-white border border-slate-200 p-3.5 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-bold text-[#181c28]">#YYL-8804</span>
                        <span class="bg-[#eef2f9] px-2 py-0.5 text-[10px] font-bold text-[#181c28]">[SELESAI]</span>
                    </div>
                    <div class="text-xs text-[#181c28]">
                        Cuci Bedcover Besar (1 Pcs)
                    </div>
                    <div class="flex items-center justify-between pt-1 text-[11px] text-slate-500 border-t border-slate-100">
                        <div>
                            <span>18 Okt 2024</span>
                            <span class="mx-1">|</span>
                            <span class="font-bold text-[#181c28]">Rp 35.000</span>
                        </div>
                        <a class="font-bold text-[#181c28] hover:underline" href="riwayat.php#YYL-8804">[LIHAT DETAIL]</a>
                    </div>
                </article>

                <article class="bg-white border border-slate-200 p-3.5 space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-bold text-[#181c28]">#YYL-8712</span>
                        <span class="bg-[#eef2f9] px-2 py-0.5 text-[10px] font-bold text-[#181c28]">[SELESAI]</span>
                    </div>
                    <div class="text-xs text-[#181c28]">
                        Setrika Saja (3.2 Kg)
                    </div>
                    <div class="flex items-center justify-between pt-1 text-[11px] text-slate-500 border-t border-slate-100">
                        <div>
                            <span>12 Okt 2024</span>
                            <span class="mx-1">|</span>
                            <span class="font-bold text-[#181c28]">Rp 19.200</span>
                        </div>
                        <a class="font-bold text-[#181c28] hover:underline" href="riwayat.php#YYL-8712">[LIHAT DETAIL]</a>
                    </div>
                </article>
            </section>
            <!-- END: RecentHistorySection -->
        </main>
        <!-- END: MainContent -->

        <?php require __DIR__ . '/includes/bottom-nav.php'; ?>
    </div>
</body>

</html>