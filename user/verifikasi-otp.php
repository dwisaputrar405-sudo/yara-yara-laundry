<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
    <title>02. Registrasi &amp; Verifikasi OTP</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;700;800&amp;display=swap" rel="stylesheet">
    <style data-purpose="typography">
        body {
            font-family: 'JetBrains Mono', ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        }
    </style>
</head>

<body class="bg-[#EEF4FA] text-[#0F2854] antialiased min-h-screen flex flex-col">
    <div class="max-w-md w-full mx-auto min-h-screen bg-[#EEF4FA] border-x border-[#DCEAF7] flex flex-col">
        <main class="w-full px-4 py-8 flex-1 flex flex-col">
            <div class="my-auto flex flex-col gap-4 w-full">
                <section data-purpose="page-header">
                    <h1 class="text-xl font-extrabold tracking-tight leading-tight uppercase mb-3">
                        VERIFIKASI OTP
                    </h1>
                    <div class="flex items-center justify-between bg-[#DCEAF7]/60 p-2 rounded border border-[#C9D9EA] text-xs">
                        <span class="text-slate-600 font-bold px-2 py-1">[1] SELESAI [v]</span>
                        <span class="text-slate-400 select-none tracking-widest">====</span>
                        <span class="bg-[#0F2854] text-white font-bold px-3 py-1.5 rounded text-[11px] whitespace-nowrap">[2] VERIFIKASI OTP</span>
                    </div>
                </section>

                <section class="bg-[#F8FBFF] border border-[#DCEAF7] p-4" data-purpose="data-summary">
                    <div class="flex items-center justify-between bg-[#E2EEF9] px-2.5 py-1.5 mb-3">
                        <span class="text-[11px] font-bold tracking-tight">[SEKSI 1. DATA PENDAFTARAN]</span>
                        <span class="text-[10px] font-medium text-slate-500">[TERKUNCI]</span>
                    </div>
                    <div class="space-y-2 text-xs">
                        <div class="flex justify-between items-baseline gap-2">
                            <span class="text-slate-500 uppercase">Nama</span>
                            <span id="otp-reg-name" class="font-bold text-right">-</span>
                        </div>
                        <div class="flex justify-between items-baseline gap-2">
                            <span class="text-slate-500 uppercase">Telepon</span>
                            <span id="otp-reg-phone" class="font-bold text-right">-</span>
                        </div>
                    </div>
                    <div class="mt-3 pt-2.5 border-t border-[#E2EEF9] text-right">
                        <a class="text-[11px] font-bold underline text-[#0F2854] hover:text-slate-600" href="register.php">
                            [UBAH DATA]
                        </a>
                    </div>
                </section>

                <section class="bg-[#F8FBFF] border border-[#DCEAF7] p-4" data-purpose="otp-verification">
                    <div class="bg-[#E2EEF9] px-2.5 py-1.5 mb-3">
                        <span class="text-[11px] font-bold tracking-tight">[SEKSI 2. INPUT KODE OTP]</span>
                    </div>
                    <p class="text-xs text-slate-700 mb-4">
                        Masukkan 6 digit kode yang dikirim ke <strong id="otp-target-phone" class="text-[#0F2854]">-</strong>.
                    </p>
                    <div class="grid grid-cols-6 gap-2 mb-4" data-purpose="otp-input-group" id="otp-container">
                        <input aria-label="Digit 1" class="otp-input w-full h-12 text-center text-lg font-extrabold bg-[#E2EEF9] border border-[#C9D9EA] rounded-none focus:border-[#1C4D8D] focus:ring-0 focus:bg-[#F8FBFF] placeholder:text-slate-400" inputmode="numeric" maxlength="1" placeholder="_" type="text">
                        <input aria-label="Digit 2" class="otp-input w-full h-12 text-center text-lg font-extrabold bg-[#E2EEF9] border border-[#C9D9EA] rounded-none focus:border-[#1C4D8D] focus:ring-0 focus:bg-[#F8FBFF] placeholder:text-slate-400" inputmode="numeric" maxlength="1" placeholder="_" type="text">
                        <input aria-label="Digit 3" class="otp-input w-full h-12 text-center text-lg font-extrabold bg-[#E2EEF9] border border-[#C9D9EA] rounded-none focus:border-[#1C4D8D] focus:ring-0 focus:bg-[#F8FBFF] placeholder:text-slate-400" inputmode="numeric" maxlength="1" placeholder="_" type="text">
                        <input aria-label="Digit 4" class="otp-input w-full h-12 text-center text-lg font-extrabold bg-[#E2EEF9] border border-[#C9D9EA] rounded-none focus:border-[#1C4D8D] focus:ring-0 focus:bg-[#F8FBFF] placeholder:text-slate-400" inputmode="numeric" maxlength="1" placeholder="_" type="text">
                        <input aria-label="Digit 5" class="otp-input w-full h-12 text-center text-lg font-extrabold bg-[#E2EEF9] border border-[#C9D9EA] rounded-none focus:border-[#1C4D8D] focus:ring-0 focus:bg-[#F8FBFF] placeholder:text-slate-400" inputmode="numeric" maxlength="1" placeholder="_" type="text">
                        <input aria-label="Digit 6" class="otp-input w-full h-12 text-center text-lg font-extrabold bg-[#E2EEF9] border border-[#C9D9EA] rounded-none focus:border-[#1C4D8D] focus:ring-0 focus:bg-[#F8FBFF] placeholder:text-slate-400" inputmode="numeric" maxlength="1" placeholder="_" type="text">
                    </div>
                    <div class="bg-[#E2EEF9] px-3 py-2.5 text-center space-y-1">
                        <div class="text-xs font-medium text-[#0F2854]">
                            Sisa waktu: <strong class="font-extrabold" id="otp-timer">05:00</strong>
                        </div>
                        <button id="btn-resend-otp" class="text-[11px] font-bold text-slate-500 hover:text-[#0F2854] underline" type="button">
                            [KIRIM ULANG KODE]
                        </button>
                    </div>
                </section>

                <section class="space-y-3 mt-1" data-purpose="terms-and-submit">
                    <label class="flex items-start gap-2.5 bg-[#F8FBFF] border border-[#DCEAF7] p-3 cursor-pointer">
                        <input id="agree-terms" checked class="mt-0.5 w-4 h-4 rounded-none border-[#0F2854] text-[#0F2854] focus:ring-0" type="checkbox">
                        <span class="text-[11px] leading-relaxed text-slate-700">
                            Saya menyetujui <a class="underline font-bold text-[#0F2854]" href="#">Ketentuan Layanan</a> &amp; <a class="underline font-bold text-[#0F2854]" href="#">Kebijakan Privasi</a> Yara Yara Laundry.
                        </span>
                    </label>

                    <button id="btn-verify-otp" class="w-full bg-[#0F2854] hover:bg-[#1C4D8D] active:bg-[#0F2854] text-white font-bold text-xs tracking-wider py-3.5 px-4 rounded-none transition-colors" type="button">
                        [VERIFIKASI &amp; AKTIFKAN AKUN]
                    </button>

                    <div class="text-center pt-1">
                        <a class="text-[11px] font-bold text-slate-500 hover:text-[#0F2854] underline" href="login.php">
                            [BATALKAN]
                        </a>
                    </div>
                </section>
            </div>
        </main>
    </div>

    <script>
        const reg = JSON.parse(sessionStorage.getItem('yaraRegData') || '{}');
        if (reg.name) document.getElementById('otp-reg-name').textContent = reg.name;
        if (reg.phone) {
            document.getElementById('otp-reg-phone').textContent = reg.phone;
            document.getElementById('otp-target-phone').textContent = reg.phone;
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

        let remaining = 300;
        const timerEl = document.getElementById('otp-timer');
        setInterval(() => {
            if (remaining > 0) {
                remaining--;
                timerEl.textContent = `${String(Math.floor(remaining / 60)).padStart(2, '0')}:${String(remaining % 60).padStart(2, '0')}`;
            }
        }, 1000);

        document.getElementById('btn-resend-otp').addEventListener('click', async () => {
            const res = await fetch('../api/user.php?route=auth&action=request_otp', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ name: reg.name, phone: reg.phone, password: reg.password, purpose: 'REGISTRASI' })
            });
            const data = await res.json();
            if (!res.ok) return alert(data.error || 'Gagal kirim ulang.');
            remaining = 300;
            alert('SIMULASI WA OTP BARU: ' + data.demo_otp);
        });

        document.getElementById('btn-verify-otp').addEventListener('click', async () => {
            if (!document.getElementById('agree-terms').checked) {
                return alert('Silakan centang persetujuan Ketentuan Layanan terlebih dahulu.');
            }
            const otp = Array.from(otpInputs).map(i => i.value).join('');
            if (otp.length < 6) return alert('Masukkan 6 digit kode OTP.');

            const res = await fetch('../api/user.php?route=auth&action=verify_otp', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ phone: reg.phone, name: reg.name, password: reg.password, otp, purpose: 'REGISTRASI' })
            });
            const data = await res.json();
            if (!res.ok || !data.success) return alert(data.error || 'Verifikasi gagal.');

            sessionStorage.removeItem('yaraRegData');
            window.location.href = 'index.php';
        });
    </script>
</body>

</html>