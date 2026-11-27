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
            <div class="my-auto w-full">
                <h1 class="text-2xl font-extrabold tracking-tight text-[#0F2854] mb-4">REGISTRASI &amp; INPUT DATA</h1>

                <div class="flex items-center gap-3 mb-6 bg-[#DCEAF7]/60 p-2 rounded border border-[#C9D9EA] text-xs">
                    <div class="bg-[#0F2854] text-white text-xs font-bold px-3 py-1.5 rounded">[1] INPUT DATA</div>
                    <div class="text-slate-400 text-xs">====</div>
                    <div class="text-slate-500 text-xs px-3 py-1.5">[2] VERIFIKASI OTP</div>
                </div>

                <form id="registration-form">
                    <div class="border border-[#DCEAF7] bg-[#F8FBFF] p-4 shadow-sm mb-4">
                        <div class="bg-[#E2EEF9] px-3 py-2 text-xs font-bold text-[#0F2854] tracking-wider mb-4 flex justify-between items-center">
                            <span>[SEKSI 1. DATA PENDAFTARAN]</span>
                            <span class="text-slate-500 font-normal">[WAJIB]</span>
                        </div>

                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1" for="full-name">Nama Lengkap</label>
                        <input class="w-full bg-[#F5F9FE] border border-[#C9D9EA] text-sm px-3 py-2 text-[#0F2854] focus:outline-none focus:border-[#1C4D8D] mb-3" id="full-name" name="full-name" placeholder="Masukkan nama lengkap" required type="text">

                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1" for="phone-number">Nomor WhatsApp / HP</label>
                        <div class="flex mb-3">
                            <span class="inline-flex items-center px-3 border border-r-0 border-[#C9D9EA] bg-[#DCEAF7] text-slate-700 text-sm font-bold">+62</span>
                            <input class="flex-1 bg-[#F5F9FE] border border-[#C9D9EA] text-sm px-3 py-2 text-[#0F2854] focus:outline-none focus:border-[#1C4D8D]" id="phone-number" name="phone-number" placeholder="812-3456-7890" required type="tel">
                        </div>
                        <p class="text-[11px] text-slate-500 mb-3">*Kode verifikasi OTP 6-digit akan dikirim ke nomor ini.</p>

                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1" for="password">Kata Sandi Baru</label>
                        <div class="flex relative">
                            <input class="w-full bg-[#F5F9FE] border border-[#C9D9EA] text-sm px-3 py-2 text-[#0F2854] focus:outline-none focus:border-[#1C4D8D]" id="password" name="password" placeholder="Minimal 6 karakter" required minlength="6" type="password">
                            <button class="border border-l-0 border-[#C9D9EA] bg-[#EAF2FA] hover:bg-[#DCEAF7] px-3 text-xs text-slate-700 font-bold" id="toggle-pw-btn" type="button">[TAMPILKAN]</button>
                        </div>
                    </div>

                    <button class="w-full bg-[#0F2854] hover:bg-[#1C4D8D] text-white font-bold text-sm py-3 px-4 shadow transition-colors mb-3 flex items-center justify-center" type="submit">
                        [LANJUT KE VERIFIKASI OTP →]
                    </button>

                    <div class="text-center">
                        <a href="login.php" class="text-xs text-slate-500 hover:text-[#0F2854] font-medium hover:underline">[BATALKAN]</a>
                    </div>
                    <div class="text-center mt-4 text-xs text-slate-500">
                        <p>Sudah punya akun? <a href="login.php" class="font-bold text-[#0F2854] hover:underline">[MASUK]</a></p>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <script>
        const togglePwBtn = document.getElementById('toggle-pw-btn');
        const pwInput = document.getElementById('password');
        togglePwBtn.addEventListener('click', () => {
            const show = pwInput.type === 'password';
            pwInput.type = show ? 'text' : 'password';
            togglePwBtn.textContent = show ? '[SEMBUNYIKAN]' : '[TAMPILKAN]';
        });

        document.getElementById('registration-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            const name = document.getElementById('full-name').value.trim();
            const phone = document.getElementById('phone-number').value.trim();
            const password = pwInput.value;

            const res = await fetch('../api/user.php?route=auth&action=request_otp', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ name, phone, password, purpose: 'REGISTRASI' })
            });
            const data = await res.json();
            if (!res.ok || !data.success) return alert(data.error || 'Gagal mengirim OTP.');

            sessionStorage.setItem('yaraRegData', JSON.stringify({ name, phone: data.phone, password }));
            // alert('SIMULASI WA OTP: Kode verifikasi Anda adalah ' + data.demo_otp);
            alert('Kode OTP telah dikirim ke WhatsApp ' + data.phone);
            window.location.href = 'verifikasi-otp.php';
        });
    </script>
</body>

</html>