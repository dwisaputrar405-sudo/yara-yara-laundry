<?php
$activeNav = 'profil';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
    <title>Profil Pengguna - YARA YARA</title>
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
</head>

<body class="min-h-screen pb-24">
    <div class="max-w-md mx-auto min-h-screen bg-[#f4f5f7] border-x border-slate-200 flex flex-col">
        <?php require __DIR__ . '/includes/header.php'; ?>

        <!-- BEGIN: MainContent -->
        <main class="flex-1 px-4 py-5 space-y-6">
            <div class="flex flex-col w-full space-y-4">
                <div class="flex items-center">
                    <a aria-label="Kembali" href="index.php" class="flex items-center gap-1 border border-[#181c28] px-2.5 py-1 text-xs font-bold tracking-wider uppercase bg-white hover:bg-slate-100 transition-colors text-[#181c28] shadow-sm rounded">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"></path>
                        </svg>
                        <span>KEMBALI</span>
                    </a>
                    <span class="font-bold tracking-wider text-slate-900 uppercase ml-2 text-base">[PROFIL PENGGUNA]</span>
                </div>

                <section class="bg-white border border-slate-200 p-4 shadow-sm">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-[#181c28] text-white flex items-center justify-center font-bold text-base tracking-widest rounded-none shadow-sm">
                                [BS]
                            </div>
                            <div>
                                <h1 id="profile-name-display" class="text-base font-bold tracking-tight text-slate-900 leading-tight">Budi Santoso</h1>
                                <p id="profile-phone-display" class="text-xs text-slate-500 mt-0.5">+62 812-3456-7890</p>
                            </div>
                        </div>
                        <span class="text-[10px] font-bold text-emerald-800 bg-emerald-100 px-2 py-0.5 tracking-wider uppercase rounded-none">
                            AKTIF
                        </span>
                    </div>
                    <div class="bg-[#eef2f9] px-3 py-2 flex items-center justify-between text-[11px] text-slate-700">
                        <span class="font-bold tracking-tight">[MEMBER TERVERIFIKASI]</span>
                        <span class="text-slate-500 text-[10px]">ID: YR-884920</span>
                    </div>
                </section>

                <!-- Quick Metrics -->
                <section class="grid grid-cols-2 gap-2">
                    <div class="bg-white border border-slate-200 p-3 shadow-sm flex flex-col justify-between">
                        <span class="text-[10px] text-slate-400 uppercase tracking-wider">TOTAL CUCIAN</span>
                        <div class="flex items-baseline gap-1 mt-1">
                            <span class="text-lg font-bold text-slate-900">24</span>
                            <span class="text-[10px] text-slate-500">Order</span>
                        </div>
                    </div>
                    <div class="bg-white border border-slate-200 p-3 shadow-sm flex flex-col justify-between">
                        <span class="text-[10px] text-slate-400 uppercase tracking-wider">POIN REWARD</span>
                        <div class="flex items-baseline gap-1 mt-1">
                            <span class="text-lg font-bold text-slate-900">450</span>
                            <span class="text-[10px] text-slate-500">PTS</span>
                        </div>
                    </div>
                </section>

                <!-- Menu Akses Cepat -->
                <div class="space-y-1.5">
                    <p class="text-[10px] tracking-widest text-slate-400 uppercase px-1">DATA AKUN &amp; LAYANAN</p>
                    <div class="bg-white border border-slate-200 shadow-sm divide-y divide-slate-100 overflow-hidden">
                        <button type="button" onclick="openProfileModal('modal-edit-profile')" class="w-full text-left block p-3.5 hover:bg-slate-50 transition-colors bg-white">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-900 tracking-tight">[EDIT DATA PROFIL]</span>
                                <span class="text-slate-400">→</span>
                            </div>
                            <p class="text-[11px] text-slate-500 mt-0.5">Nama &amp; Nomor Kontak</p>
                        </button>

                        <button type="button" onclick="openProfileModal('modal-edit-address')" class="w-full text-left block p-3.5 hover:bg-slate-50 transition-colors bg-slate-50/50">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-900 tracking-tight">[ALAMAT TERSIMPAN]</span>
                                <span class="text-slate-400">→</span>
                            </div>
                            <p id="profile-address-display" class="text-[11px] text-slate-600 mt-0.5 truncate">Jl. Sasak Dempul, Bekasi</p>
                        </button>

                        <a class="block p-3.5 hover:bg-slate-50 transition-colors bg-white" href="riwayat.php">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-900 tracking-tight">[RIWAYAT PESANAN LENGKAP]</span>
                                <span class="text-slate-400">→</span>
                            </div>
                            <p class="text-[11px] text-slate-500 mt-0.5">Daftar transaksi, nota &amp; resi digital</p>
                        </a>

                        <a class="block p-3.5 hover:bg-slate-50 transition-colors bg-slate-50/50" href="https://wa.me/6285719420746" rel="noopener noreferrer" target="_blank">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-900 tracking-tight">[PUSAT BANTUAN &amp; CS]</span>
                                <span class="text-emerald-700 text-[10px] font-bold">[ONLINE]</span>
                            </div>
                            <p class="text-[11px] text-slate-500 mt-0.5">WhatsApp Admin Outlet Cabang</p>
                        </a>
                    </div>
                </div>

                <!-- Keamanan & Sesi -->
                <div class="space-y-1.5 pt-1">
                    <p class="text-[10px] tracking-widest text-slate-400 uppercase px-1">KEAMANAN &amp; SESI</p>
                    <div class="bg-white border border-slate-200 shadow-sm overflow-hidden">
                        <button type="button" onclick="openProfileModal('modal-edit-password')" class="w-full text-left p-3.5 hover:bg-slate-50 transition-colors flex items-center justify-between">
                            <div>
                                <span class="text-xs font-bold text-slate-900 tracking-tight">[GANTI KATA SANDI]</span>
                                <p class="text-[11px] text-slate-500 mt-0.5">Pembaruan PIN &amp; password masuk</p>
                            </div>
                            <span class="text-slate-400 text-xs">→</span>
                        </button>
                    </div>

                    <div class="pt-2">
                        <button class="w-full bg-rose-50 hover:bg-rose-100 border border-rose-200 text-rose-800 p-3.5 text-center transition-colors shadow-sm flex items-center justify-center gap-2" id="logoutBtn" type="button">
                            <span class="text-xs font-bold tracking-wider">[ KELUAR DARI AKUN / LOGOUT ]</span>
                        </button>
                        <p class="text-[10px] text-center text-slate-400 mt-2">
                            YARA LAUNDRY WEB - V.2.4.0
                        </p>
                    </div>
                </div>
            </div>
        </main>
        <!-- END: MainContent -->

        <?php require __DIR__ . '/includes/bottom-nav.php'; ?>

        <!-- Modal Edit Profil -->
        <div id="modal-edit-profile" class="hidden fixed inset-0 z-50 bg-slate-900/60 flex items-center justify-center p-4">
            <div class="w-full max-w-[340px] bg-white border-2 border-[#181c28] p-4 space-y-3 text-xs">
                <div class="flex items-center justify-between border-b border-slate-200 pb-2 font-bold">
                    <span>[EDIT DATA PROFIL]</span>
                    <button type="button" onclick="closeProfileModal('modal-edit-profile')">[X]</button>
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase mb-1">NAMA LENGKAP</label>
                    <input id="input-profile-name" type="text" value="Budi Santoso" class="w-full border border-slate-400 px-2.5 py-2 text-xs">
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase mb-1">NO WHATSAPP</label>
                    <input id="input-profile-phone" type="text" value="+62 812-3456-7890" class="w-full border border-slate-400 px-2.5 py-2 text-xs">
                </div>
                <button type="button" onclick="saveProfileData()" class="w-full bg-[#181c28] text-white font-bold py-2.5">[SIMPAN PERUBAHAN]</button>
            </div>
        </div>

        <!-- Modal Alamat Tersimpan -->
        <div id="modal-edit-address" class="hidden fixed inset-0 z-50 bg-slate-900/60 flex items-center justify-center p-4">
            <div class="w-full max-w-[340px] bg-white border-2 border-[#181c28] p-4 space-y-3 text-xs">
                <div class="flex items-center justify-between border-b border-slate-200 pb-2 font-bold">
                    <span>[ALAMAT ANTAR-JEMPUT]</span>
                    <button type="button" onclick="closeProfileModal('modal-edit-address')">[X]</button>
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase mb-1">ALAMAT LENGKAP</label>
                    <textarea id="input-profile-address" rows="3" class="w-full border border-slate-400 p-2 text-xs">Jl. Sasak Dempul, Bekasi</textarea>
                </div>
                <button type="button" onclick="saveAddressData()" class="w-full bg-[#181c28] text-white font-bold py-2.5">[SIMPAN ALAMAT]</button>
            </div>
        </div>

        <!-- Modal Ganti Kata Sandi -->
        <div id="modal-edit-password" class="hidden fixed inset-0 z-50 bg-slate-900/60 flex items-center justify-center p-4">
            <div class="w-full max-w-[340px] bg-white border-2 border-[#181c28] p-4 space-y-3 text-xs">
                <div class="flex items-center justify-between border-b border-slate-200 pb-2 font-bold">
                    <span>[GANTI KATA SANDI]</span>
                    <button type="button" onclick="closeProfileModal('modal-edit-password')">[X]</button>
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase mb-1">KATA SANDI BARU</label>
                    <input type="password" placeholder="Minimal 6 karakter" class="w-full border border-slate-400 px-2.5 py-2 text-xs">
                </div>
                <button type="button" onclick="closeProfileModal('modal-edit-password'); alert('Kata sandi berhasil diperbarui.');" class="w-full bg-[#181c28] text-white font-bold py-2.5">[PERBARUI SANDI]</button>
            </div>
        </div>
    </div>

    <script>
        function openProfileModal(id) { document.getElementById(id).classList.remove('hidden'); }
        function closeProfileModal(id) { document.getElementById(id).classList.add('hidden'); }

        function saveProfileData() {
            document.getElementById('profile-name-display').textContent = document.getElementById('input-profile-name').value;
            document.getElementById('profile-phone-display').textContent = document.getElementById('input-profile-phone').value;
            closeProfileModal('modal-edit-profile');
        }

        function saveAddressData() {
            document.getElementById('profile-address-display').textContent = document.getElementById('input-profile-address').value;
            closeProfileModal('modal-edit-address');
        }

        document.getElementById('logoutBtn')?.addEventListener('click', function () {
            if (confirm('Konfirmasi: Apakah Anda yakin ingin keluar dari akun Yara Laundry?')) {
                this.textContent = '[ SEDANG KELUAR... ]';
                this.classList.add('opacity-75');
                setTimeout(() => { window.location.href = 'login.php'; }, 500);
            }
        });
    </script>
</body>

</html>