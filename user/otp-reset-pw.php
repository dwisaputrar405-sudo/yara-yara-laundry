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
            background-color: #f4f5f7;
            color: #181c28;
            -webkit-tap-highlight-color: transparent;
        }
    </style>
</head>

<body class="min-h-screen flex flex-col items-center justify-center bg-[#f4f5f7]">
    <div class="w-full max-w-md min-h-screen bg-[#f4f5f7] border-x border-slate-200 flex flex-col" data-purpose="mobile-app-shell">
        <!-- BEGIN: MainContent -->
        <main class="flex-1 p-4 py-8 flex flex-col" data-purpose="otp-reset-main-content">
            <div class="my-auto flex flex-col gap-4 w-full">
                <section class="bg-white border-2 border-[#181c28] p-4" data-purpose="page-header-card">
                    <h1 class="text-base font-bold tracking-tight uppercase">
                        VERIFIKASI OTP PEMULIHAN
                    </h1>
                    <p class="text-xs text-slate-600 mt-1">
                        Masukkan 6 digit kode OTP untuk mengatur kata sandi baru.
                    </p>
                </section>

                <section class="bg-white border-2 border-[#181c28] p-4 flex flex-col gap-4" data-purpose="otp-form-card">
                    <div class="bg-[#f4f5f7] border border-[#181c28] px-3 py-2 flex items-center justify-between gap-2 text-xs">
                        <div>
                            <span class="text-[10px] text-slate-500 block uppercase">TUJUAN OTP</span>
                            <strong id="reset-target-phone" class="text-[#181c28]">+62 812-3456-7890</strong>
                        </div>
                        <a href="reset-pw.php" class="text-[11px] font-bold uppercase underline hover:text-slate-600">
                            [UBAH]
                        </a>
                    </div>

                    <form class="flex flex-col gap-4" id="otp-reset-form" method="POST">
                        <div class="grid grid-cols-6 gap-2" data-purpose="otp-input-group">
                            <input aria-label="Digit 1" class="otp-input w-full h-12 text-center text-lg font-extrabold bg-white border-2 border-[#181c28] focus:bg-[#f4f5f7] focus:ring-0 focus:outline-none" inputmode="numeric" maxlength="1" type="text" value="4" />
                            <input aria-label="Digit 2" class="otp-input w-full h-12 text-center text-lg font-extrabold bg-white border-2 border-[#181c28] focus:bg-[#f4f5f7] focus:ring-0 focus:outline-none" inputmode="numeric" maxlength="1" type="text" value="9" />
                            <input aria-label="Digit 3" class="otp-input w-full h-12 text-center text-lg font-extrabold bg-white border-2 border-[#181c28] focus:bg-[#f4f5f7] focus:ring-0 focus:outline-none" inputmode="numeric" maxlength="1" type="text" value="2" />
                            <input aria-label="Digit 4" class="otp-input w-full h-12 text-center text-lg font-extrabold bg-white border-2 border-[#181c28] focus:bg-[#f4f5f7] focus:ring-0 focus:outline-none placeholder:text-slate-400" inputmode="numeric" maxlength="1" placeholder="_" type="text" />
                            <input aria-label="Digit 5" class="otp-input w-full h-12 text-center text-lg font-extrabold bg-white border-2 border-[#181c28] focus:bg-[#f4f5f7] focus:ring-0 focus:outline-none placeholder:text-slate-400" inputmode="numeric" maxlength="1" placeholder="_" type="text" />
                            <input aria-label="Digit 6" class="otp-input w-full h-12 text-center text-lg font-extrabold bg-white border-2 border-[#181c28] focus:bg-[#f4f5f7] focus:ring-0 focus:outline-none placeholder:text-slate-400" inputmode="numeric" maxlength="1" placeholder="_" type="text" />
                        </div>

                        <div class="bg-[#f4f5f7] border border-slate-300 px-3 py-2.5 text-center space-y-1">
                            <div class="text-xs font-medium text-[#181c28]">
                                Sisa waktu: <strong class="font-extrabold" id="otp-timer">01:45</strong>
                            </div>
                            <button id="btn-resend-otp" class="text-[11px] font-bold text-slate-600 hover:text-[#181c28] underline uppercase" type="button">
                                [KIRIM ULANG KODE OTP]
                            </button>
                        </div>

                        <button class="w-full bg-[#181c28] text-white py-3.5 px-4 text-xs font-bold uppercase tracking-wider border-2 border-[#181c28] hover:bg-slate-800 active:translate-y-0.5 transition-transform" type="submit">
                            [VERIFIKASI &amp; ATUR SANDI BARU -&gt;]
                        </button>

                        <a href="login.php" class="w-full bg-white text-[#181c28] py-3 px-4 text-xs font-bold uppercase tracking-wide border-2 border-dashed border-[#181c28] text-center hover:bg-slate-50">
                            [BATALKAN / KEMBALI KE LOGIN]
                        </a>
                    </form>
                </section>

                <footer class="bg-white border-2 border-[#181c28] p-3.5 text-xs" data-purpose="operational-help-footer">
                    <div class="flex items-center gap-1.5 font-bold uppercase mb-1">
                        <span class="text-red-700">[!]</span>
                        <span>BANTUAN TEKNIS</span>
                    </div>
                    <p class="text-[11px] text-slate-700 leading-relaxed">
                        WhatsApp Admin: <strong class="text-[#181c28]">+62 811-0000-YYL</strong> (07.00 - 21.00 WIB). Jangan bagikan kode OTP kepada siapa pun.
                    </p>
                </footer>
            </div>
        </main>
        <!-- END: MainContent -->
    </div>

    <script>
        const savedPhone = sessionStorage.getItem('yaraResetPhone');
        if (savedPhone) {
            document.getElementById('reset-target-phone').textContent = savedPhone;
        }

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

        let remaining = 105;
        const timerEl = document.getElementById('otp-timer');
        setInterval(() => {
            if (remaining > 0) {
                remaining--;
                const m = String(Math.floor(remaining / 60)).padStart(2, '0');
                const s = String(remaining % 60).padStart(2, '0');
                timerEl.textContent = `${m}:${s}`;
            }
        }, 1000);

        document.getElementById('btn-resend-otp').addEventListener('click', () => {
            remaining = 105;
            alert('Kode OTP pemulihan baru telah dikirim.');
        });

        document.getElementById('otp-reset-form').addEventListener('submit', (e) => {
            e.preventDefault();
            const code = Array.from(otpInputs).map(i => i.value).join('');
            if (code.length < 6) {
                alert('Masukkan 6 digit kode OTP terlebih dahulu.');
                return;
            }
            window.location.href = 'login.php';
        });
    </script>
</body>

</html>