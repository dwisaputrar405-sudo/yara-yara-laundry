<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
    <title>Verifikasi OTP Pemulihan - Yara Yara Laundry</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet">
    <style data-purpose="typography-and-reset">
        body {
            font-family: 'JetBrains Mono', ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            background-color: #EEF4FA;
            color: #0F2854;
            -webkit-tap-highlight-color: transparent;
        }
    </style>
</head>

<body class="min-h-screen flex flex-col items-center justify-center bg-[#EEF4FA]">
    <div class="w-full max-w-md min-h-screen bg-[#EEF4FA] border-x border-[#DCEAF7] flex flex-col">
        <main class="flex-1 p-4 py-8 flex flex-col">
            <div class="my-auto flex flex-col gap-4 w-full">
                <section class="bg-[#F8FBFF] border-2 border-[#0F2854] p-4">
                    <h1 class="text-base font-bold tracking-tight uppercase">
                        VERIFIKASI OTP PEMULIHAN
                    </h1>
                    <p class="text-xs text-slate-600 mt-1">
                        Masukkan 6 digit kode OTP untuk mengatur kata sandi baru.
                    </p>
                </section>

                <section class="bg-[#F8FBFF] border-2 border-[#0F2854] p-4 flex flex-col gap-4">
                    <div class="bg-[#EEF4FA] border border-[#0F2854] px-3 py-2 flex items-center justify-between gap-2 text-xs">
                        <div>
                            <span class="text-[10px] text-slate-500 block uppercase">TUJUAN OTP</span>
                            <strong id="reset-target-phone" class="text-[#0F2854]">-</strong>
                        </div>
                        <a href="reset-pw.php" class="text-[11px] font-bold uppercase underline hover:text-slate-600">
                            [UBAH]
                        </a>
                    </div>

                    <form class="flex flex-col gap-4" id="otp-reset-form" method="POST">
                        <div class="grid grid-cols-6 gap-2">
                            <input aria-label="Digit 1" class="otp-input w-full h-12 text-center text-lg font-extrabold bg-[#F8FBFF] border-2 border-[#0F2854] focus:bg-[#EEF4FA] focus:ring-0 focus:outline-none placeholder:text-slate-400" inputmode="numeric" maxlength="1" placeholder="_" type="text" />
                            <input aria-label="Digit 2" class="otp-input w-full h-12 text-center text-lg font-extrabold bg-[#F8FBFF] border-2 border-[#0F2854] focus:bg-[#EEF4FA] focus:ring-0 focus:outline-none placeholder:text-slate-400" inputmode="numeric" maxlength="1" placeholder="_" type="text" />
                            <input aria-label="Digit 3" class="otp-input w-full h-12 text-center text-lg font-extrabold bg-[#F8FBFF] border-2 border-[#0F2854] focus:bg-[#EEF4FA] focus:ring-0 focus:outline-none placeholder:text-slate-400" inputmode="numeric" maxlength="1" placeholder="_" type="text" />
                            <input aria-label="Digit 4" class="otp-input w-full h-12 text-center text-lg font-extrabold bg-[#F8FBFF] border-2 border-[#0F2854] focus:bg-[#EEF4FA] focus:ring-0 focus:outline-none placeholder:text-slate-400" inputmode="numeric" maxlength="1" placeholder="_" type="text" />
                            <input aria-label="Digit 5" class="otp-input w-full h-12 text-center text-lg font-extrabold bg-[#F8FBFF] border-2 border-[#0F2854] focus:bg-[#EEF4FA] focus:ring-0 focus:outline-none placeholder:text-slate-400" inputmode="numeric" maxlength="1" placeholder="_" type="text" />
                            <input aria-label="Digit 6" class="otp-input w-full h-12 text-center text-lg font-extrabold bg-[#F8FBFF] border-2 border-[#0F2854] focus:bg-[#EEF4FA] focus:ring-0 focus:outline-none placeholder:text-slate-400" inputmode="numeric" maxlength="1" placeholder="_" type="text" />
                        </div>

                        <div class="bg-[#EEF4FA] border border-[#C9D9EA] px-3 py-2.5 text-center space-y-1">
                            <div class="text-xs font-medium text-[#0F2854]">
                                Sisa waktu: <strong class="font-extrabold" id="otp-timer">05:00</strong>
                            </div>
                            <button id="btn-resend-otp" disabled class="text-[11px] font-bold text-slate-400 cursor-not-allowed uppercase" type="button">
                                [TUNGGU 60 DETIK UNTUK KIRIM ULANG]
                            </button>
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-bold uppercase tracking-wide" for="new-reset-password">KATA SANDI BARU [MIN. 6 KARAKTER]</label>
                            <input id="new-reset-password" type="password" required minlength="6" placeholder="••••••••" class="w-full px-3 py-2.5 text-sm bg-[#F8FBFF] border-2 border-[#0F2854] focus:ring-0 focus:outline-none" />
                        </div>

                        <button class="w-full bg-[#0F2854] text-white py-3.5 px-4 text-xs font-bold uppercase tracking-wider border-2 border-[#0F2854] hover:bg-[#1C4D8D] active:translate-y-0.5 transition-transform" type="submit">
                            [VERIFIKASI &amp; ATUR SANDI BARU -&gt;]
                        </button>

                        <a href="login.php" class="w-full bg-[#F8FBFF] text-[#0F2854] py-3 px-4 text-xs font-bold uppercase tracking-wide border-2 border-dashed border-[#0F2854] text-center hover:bg-[#F5F9FE]">
                            [BATALKAN / KEMBALI KE LOGIN]
                        </a>
                    </form>
                </section>

                <footer class="bg-[#F8FBFF] border-2 border-[#0F2854] p-3.5 text-xs">
                    <div class="flex items-center gap-1.5 font-bold uppercase mb-1">
                        <span class="text-red-700">[!]</span>
                        <span>BANTUAN TEKNIS</span>
                    </div>
                    <p class="text-[11px] text-slate-700 leading-relaxed">
                        WhatsApp Admin: <strong class="text-[#0F2854]">+62 851-7999-8522</strong> (07.00 - 21.00 WIB). Jangan bagikan kode OTP kepada siapa pun.
                    </p>
                </footer>
            </div>
        </main>
    </div>

    <script>
        const savedPhone = sessionStorage.getItem('yaraResetPhone') || '';
        if (savedPhone) document.getElementById('reset-target-phone').textContent = savedPhone;

        const otpInputs = document.querySelectorAll('.otp-input');
        otpInputs.forEach((input, index) => {
            input.addEventListener('input', (e) => {
                const val = e.target.value.replace(/\D/g, '');
                e.target.value = val.slice(0, 1);
                if (val && index < otpInputs.length - 1) otpInputs[index + 1].focus();
            });
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !e.target.value && index > 0) otpInputs[index - 1].focus();
            });
        });

        // Timer masa berlaku OTP (5 menit)
        let remaining = 300;
        const timerEl = document.getElementById('otp-timer');
        setInterval(() => {
            if (remaining > 0) {
                remaining--;
                timerEl.textContent = `${String(Math.floor(remaining / 60)).padStart(2, '0')}:${String(remaining % 60).padStart(2, '0')}`;
            }
        }, 1000);

        // Anti-Spam Cooldown 60 detik untuk tombol Kirim Ulang OTP
        const resendBtn = document.getElementById('btn-resend-otp');
        const COOLDOWN_SECONDS = 60;
        const STORAGE_KEY = 'yaraOtpNextResendAt';
        let isSending = false;

        function startResendCooldown(seconds) {
            const nextAllowed = Date.now() + seconds * 1000;
            sessionStorage.setItem(STORAGE_KEY, String(nextAllowed));
            updateResendButton();
        }

        function updateResendButton() {
            if (isSending) return;
            const nextAllowed = Number(sessionStorage.getItem(STORAGE_KEY) || 0);
            const diff = Math.ceil((nextAllowed - Date.now()) / 1000);

            if (diff > 0) {
                resendBtn.disabled = true;
                resendBtn.className = 'text-[11px] font-bold text-slate-400 cursor-not-allowed uppercase';
                resendBtn.textContent = `[KIRIM ULANG DALAM ${diff} DETIK]`;
            } else {
                resendBtn.disabled = false;
                resendBtn.className = 'text-[11px] font-bold text-slate-700 hover:text-[#0F2854] underline uppercase cursor-pointer';
                resendBtn.textContent = '[KIRIM ULANG KODE OTP]';
            }
        }

        // Aktifkan cooldown 60 detik sejak halaman pertama kali dibuka (OTP baru saja dikirim dari reset-pw.php)
        if (!sessionStorage.getItem(STORAGE_KEY)) {
            startResendCooldown(COOLDOWN_SECONDS);
        } else {
            updateResendButton();
        }
        setInterval(updateResendButton, 1000);

        resendBtn.addEventListener('click', async () => {
            if (resendBtn.disabled || isSending) return;
            if (!savedPhone) return alert('Nomor tujuan tidak ditemukan. Silakan kembali ke halaman sebelumnya.');

            isSending = true;
            resendBtn.disabled = true;
            resendBtn.className = 'text-[11px] font-bold text-slate-400 cursor-not-allowed uppercase';
            resendBtn.textContent = '[MENGIRIM OTP...]';

            try {
                const res = await fetch('../api/user.php?route=auth&action=request_otp', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ phone: savedPhone, purpose: 'RESET_PASSWORD' })
                });
                const data = await res.json();
                if (!res.ok) {
                    isSending = false;
                    startResendCooldown(15);
                    return alert(data.error || 'Gagal kirim ulang.');
                }
                remaining = 300;
                isSending = false;
                startResendCooldown(COOLDOWN_SECONDS);
                alert('Kode OTP baru telah dikirim ke WhatsApp Anda.');
            } catch {
                isSending = false;
                startResendCooldown(15);
                alert('Gagal terhubung ke server.');
            }
        });

        document.getElementById('otp-reset-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            const otp = Array.from(otpInputs).map(i => i.value).join('');
            const new_password = document.getElementById('new-reset-password').value;
            if (otp.length < 6) return alert('Masukkan 6 digit kode OTP terlebih dahulu.');

            const res = await fetch('../api/user.php?route=auth&action=verify_otp', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ phone: savedPhone, otp, new_password, purpose: 'RESET_PASSWORD' })
            });
            const data = await res.json();
            if (!res.ok || !data.success) return alert(data.error || 'Gagal mengatur ulang sandi.');

            sessionStorage.removeItem('yaraResetPhone');
            sessionStorage.removeItem(STORAGE_KEY);
            alert('Kata sandi berhasil diperbarui! Silakan login dengan sandi baru.');
            window.location.href = 'login.php';
        });
    </script>
</body>

</html>