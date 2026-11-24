<?php
require __DIR__ . '/includes/guard.php';
$activeNav = 'pesan';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
    <title>Konfirmasi Rincian Pesanan - Yara Yara Laundry</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet">
    <style data-purpose="typography">
        body {
            font-family: 'JetBrains Mono', ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            background-color: #f4f5f7;
            color: #181c28;
            -webkit-font-smoothing: antialiased;
        }
    </style>
    <style data-purpose="brutalist-utilities">
        .brutalist-card {
            background-color: #ffffff;
            border: 1px solid #d1d9e6;
        }

        .brutalist-inset {
            background-color: #eef2f9;
        }

        .brutalist-header-strip {
            background-color: #e2e8f0;
        }

        .shadow-brutal {
            box-shadow: 4px 4px 0px 0px rgba(24, 28, 40, 1);
        }

        .shadow-brutal-sm {
            box-shadow: 2px 2px 0px 0px rgba(24, 28, 40, 1);
        }
    </style>
</head>

<body class="min-h-screen pb-24">
    <div class="max-w-md mx-auto min-h-screen flex flex-col bg-[#f4f5f7] border-x border-slate-200 relative">
        <?php require __DIR__ . '/includes/header.php'; ?>

        <!-- BEGIN: MainContent -->
        <main class="flex-1 w-full px-4 pt-4 pb-6 space-y-4">
            <nav aria-label="Navigasi Kembali">
                <a class="inline-flex items-center gap-1.5 text-xs font-bold text-[#181c28] hover:underline" href="pesan.php">
                    <span>←</span>
                    <span>[KEMBALI KE FORM PEMESANAN]</span>
                </a>
            </nav>

            <!-- BEGIN: PageTitleSection -->
            <section class="brutalist-card p-4 space-y-2" data-purpose="page-title-block">
                <div class="inline-block bg-slate-200 px-2 py-0.5 text-[10px] font-bold tracking-wide text-[#181c28]">
                    [STATUS: DRAF SIAP DIKONFIRMASI]
                </div>
                <h1 class="text-xl font-extrabold tracking-tight text-[#181c28] leading-tight">
                    KONFIRMASI RINCIAN PESANAN
                </h1>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Periksa kembali rincian cucian Anda sebelum diterbitkan ke sistem antrean.
                </p>
            </section>
            <!-- END: PageTitleSection -->

            <!-- BEGIN: TicketCodeSection -->
            <section class="brutalist-card p-4 space-y-2" data-purpose="ticket-identification">
                <div class="flex items-start justify-between gap-2">
                    <span class="text-[10px] font-medium text-slate-500 uppercase tracking-wider leading-tight">
                        IDENTIFIKASI DOKUMEN SISTEM:
                    </span>
                    <span class="bg-[#181c28] text-white text-[10px] font-bold px-2 py-0.5 tracking-wider shrink-0">
                        MENUNGGU KONFIRMASI
                    </span>
                </div>
                <div class="text-2xl font-extrabold tracking-tight text-[#181c28]">
                    #YYL-8922
                </div>
            </section>
            <!-- END: TicketCodeSection -->

            <!-- BEGIN: CustomerAndOutletSection -->
            <section class="brutalist-card p-3 space-y-2.5" data-purpose="customer-outlet-info">
                <div class="brutalist-header-strip px-2.5 py-1.5 text-xs font-bold text-[#181c28]">
                    [1. DATA PEMESAN &amp; OUTLET]
                </div>
                <div class="brutalist-inset p-2.5 space-y-1">
                    <div class="text-[10px] text-slate-500 uppercase">NAMA PELANGGAN:</div>
                    <div class="text-xs font-bold text-[#181c28]">Budi Santoso</div>
                    <div class="text-xs font-semibold text-slate-700">+62 812-3456-7890</div>
                </div>
                <div class="brutalist-inset p-2.5 space-y-1">
                    <div class="text-[10px] text-slate-500 uppercase">OUTLET TUJUAN:</div>
                    <div class="text-xs font-bold text-[#181c28]">Gerai Pusat Yara Yara</div>
                </div>
            </section>
            <!-- END: CustomerAndOutletSection -->

            <!-- BEGIN: ServiceDetailsSection -->
            <section class="brutalist-card p-3 space-y-2.5" data-purpose="service-specification">
                <div class="brutalist-header-strip px-2.5 py-1.5 text-xs font-bold text-[#181c28]">
                    [2. RINCIAN LAYANAN &amp; BIAYA]
                </div>
                <div class="space-y-1.5 text-xs">
                    <div class="brutalist-inset px-2.5 py-2 flex justify-between items-center gap-2">
                        <span class="text-[11px] text-slate-600">PAKET LAYANAN</span>
                        <span id="conf-service" class="font-bold text-[#181c28] text-right">Cuci Komplit Reguler</span>
                    </div>
                    <div class="brutalist-inset px-2.5 py-2 flex justify-between items-center gap-2">
                        <span class="text-[11px] text-slate-600">ESTIMASI BEBAN</span>
                        <span id="conf-weight" class="font-bold text-[#181c28] text-right">4.0 Kg (@ Rp 8.000)</span>
                    </div>
                    <div class="brutalist-inset px-2.5 py-2 flex justify-between items-center gap-2">
                        <span class="text-[11px] text-slate-600">SERAH TERIMA</span>
                        <span id="conf-method" class="font-bold text-[#181c28] text-right">Ambil Sendiri</span>
                    </div>
                    <div id="conf-address-row" class="hidden brutalist-inset px-2.5 py-2 flex justify-between items-start gap-2">
                        <span class="text-[11px] text-slate-600 shrink-0">ALAMAT KURIR</span>
                        <span id="conf-address" class="font-bold text-[#181c28] text-right">-</span>
                    </div>
                </div>
                <div class="bg-slate-200 border border-slate-300 px-3 py-2.5 flex justify-between items-center">
                    <span class="text-xs font-bold text-[#181c28]">TOTAL ESTIMASI</span>
                    <span id="conf-total" class="text-base font-extrabold text-[#181c28]">RP 32.000</span>
                </div>
            </section>
            <!-- END: ServiceDetailsSection -->

            <!-- BEGIN: ShortScaleWarning -->
            <section class="bg-slate-200/80 border border-slate-300 p-3 flex items-start gap-2.5" data-purpose="scale-warning-notice">
                <svg class="w-4 h-4 text-[#181c28] shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
                <p class="text-[11px] text-[#181c28] leading-snug">
                    <strong class="font-bold">[!] CATATAN TIMBANGAN:</strong> Berat <span id="conf-warn-weight">4.0 Kg</span> adalah estimasi awal dan akan ditimbang ulang resmi oleh kasir saat serah terima di outlet.
                </p>
            </section>
            <!-- END: ShortScaleWarning -->

            <!-- BEGIN: ActionButtons -->
            <section class="space-y-2.5 pt-1" data-purpose="confirmation-actions">
                <button id="btn-publish-ticket" class="w-full bg-[#181c28] hover:bg-slate-800 active:bg-black text-white font-bold text-xs tracking-wider py-3.5 px-4 border border-[#181c28] transition-colors" type="button">
                    [KONFIRMASI &amp; TERBITKAN TIKET #YYL-8922]
                </button>
                <a href="pesan.php" class="w-full bg-white hover:bg-slate-50 text-[#181c28] font-bold text-xs tracking-wider py-3 px-4 border border-slate-300 flex items-center justify-center gap-2 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                    <span>[UBAH RINCIAN PESANAN]</span>
                </a>
            </section>
            <!-- END: ActionButtons -->
        </main>
        <!-- END: MainContent -->

        <?php require __DIR__ . '/includes/bottom-nav.php'; ?>

        <!-- BEGIN: ModalOverlay (Pesanan Berhasil Dibuat - Muncul saat tombol Konfirmasi ditekan) -->
        <div id="modal-success-ticket" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-[2px] flex items-center justify-center p-4">
            <section class="w-full max-w-[340px] bg-white border-2 border-[#181c28] shadow-brutal p-5 relative" data-purpose="order-confirmation-modal">
                <a href="index.php" aria-label="Tutup dialog" class="absolute top-2.5 right-2.5 w-6 h-6 flex items-center justify-center text-xs font-bold text-slate-500 hover:text-black border border-slate-300 hover:border-black bg-white transition-colors">
                    ✕
                </a>

                <div class="flex flex-col items-center text-center mt-1">
                    <div class="w-10 h-10 border-2 border-[#181c28] flex items-center justify-center bg-white shadow-brutal-sm mb-3">
                        <svg class="w-5 h-5 text-emerald-600 stroke-[3]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M5 13l4 4L19 7" stroke-linecap="square" stroke-linejoin="miter"></path>
                        </svg>
                    </div>
                    <div class="inline-block bg-emerald-50 border border-emerald-300 text-emerald-800 text-[9px] tracking-wider font-bold px-2 py-0.5 uppercase mb-1">
                        [STATUS: BERHASIL DIBUAT]
                    </div>
                    <h2 class="text-base font-extrabold text-[#181c28] tracking-tight uppercase">
                        PESANAN DITERBITKAN
                    </h2>
                    <p class="text-[10px] text-slate-500 mt-1 max-w-[240px] leading-relaxed">
                        Tiket antrean resmi telah masuk ke sistem operasional gerai.
                    </p>
                </div>

                <div class="mt-4 border-2 border-dashed border-slate-300 bg-slate-50/80 p-3 text-center">
                    <div class="text-[9px] text-slate-500 font-bold uppercase tracking-wider">
                        NO. TIKET / RESI PEMESANAN
                    </div>
                    <div class="text-xl font-extrabold text-[#181c28] tracking-wide mt-1">
                        #YYL-8922
                    </div>
                </div>

                <div class="mt-3.5 border-t border-b border-slate-200 py-2.5 space-y-1.5 text-[11px]">
                    <div class="flex justify-between items-baseline">
                        <span class="text-slate-500">Layanan:</span>
                        <span id="modal-service" class="font-bold text-slate-800 text-right">Cuci Komplit Reguler</span>
                    </div>
                    <div class="flex justify-between items-baseline">
                        <span class="text-slate-500">Estimasi Beban:</span>
                        <span id="modal-weight" class="font-bold text-slate-800 text-right">4.0 Kg</span>
                    </div>
                    <div class="flex justify-between items-baseline pt-1 border-t border-slate-100">
                        <span class="text-[#181c28] font-bold">Total Estimasi:</span>
                        <span id="modal-total" class="font-extrabold text-[#181c28] text-right">Rp 32.000</span>
                    </div>
                </div>

                <div class="mt-3 bg-slate-50 border-l-2 border-[#181c28] px-2.5 py-1.5 text-[10px] text-slate-600 leading-snug">
                    <span class="font-bold text-[#181c28]">[!] Catatan:</span> Tunjukkan tiket ini ke kasir saat menyerahkan pakaian untuk penimbangan.
                </div>

                <div class="mt-4 flex flex-col gap-2">
                    <a href="lacak.php" class="w-full bg-[#181c28] hover:bg-black text-white text-[11px] font-bold py-2.5 px-3 uppercase tracking-wider text-center transition-all border border-[#181c28] shadow-brutal-sm flex items-center justify-center gap-1.5">
                        <span>[ LACAK PROSES PESANAN</span>
                        <span>→ ]</span>
                    </a>
                    <a href="index.php" class="w-full bg-white hover:bg-slate-50 text-slate-800 text-[10px] font-bold py-2 px-3 uppercase tracking-wider text-center transition-all border border-slate-300">
                        [ KEMBALI KE BERANDA ]
                    </a>
                </div>
            </section>
        </div>
        <!-- END: ModalOverlay -->
    </div>

    <script>
        (function () {
            const raw = sessionStorage.getItem('yaraDraftOrder');
            if (raw) {
                const d = JSON.parse(raw);
                const weightLabel = `${d.weightText} ${d.unit === 'KG' ? 'Kg' : 'Pcs'}`;
                const totalRp = 'Rp ' + Number(d.total).toLocaleString('id-ID');

                document.getElementById('conf-service').textContent = d.serviceName;
                document.getElementById('conf-weight').textContent = `${weightLabel} (@ Rp ${Number(d.unitPrice).toLocaleString('id-ID')})`;
                document.getElementById('conf-method').textContent = d.methodLabel;
                document.getElementById('conf-total').textContent = totalRp.toUpperCase();
                document.getElementById('conf-warn-weight').textContent = weightLabel;

                if (d.deliveryFee > 0 && d.address) {
                    document.getElementById('conf-address-row').classList.remove('hidden');
                    document.getElementById('conf-address').textContent = d.address;
                }

                document.getElementById('modal-service').textContent = d.serviceName;
                document.getElementById('modal-weight').textContent = weightLabel;
                document.getElementById('modal-total').textContent = totalRp;
            }

            document.getElementById('btn-publish-ticket').addEventListener('click', () => {
                document.getElementById('modal-success-ticket').classList.remove('hidden');
            });
        })();
    </script>
</body>

</html>