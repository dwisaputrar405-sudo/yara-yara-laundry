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

<body class="bg-[#f4f5f7] text-[#181c28] antialiased min-h-screen flex flex-col">
    <div class="max-w-md w-full mx-auto min-h-screen bg-[#f4f5f7] border-x border-slate-200 flex flex-col">
        <!-- BEGIN: MainContent -->
        <main class="w-full px-4 py-8 flex-1 flex flex-col">
            <div class="my-auto w-full">
                <h1 class="text-2xl font-extrabold tracking-tight text-[#181c28] mb-4">REGISTRASI &amp; INPUT DATA</h1>

                <!-- Stepper bar -->
                <div class="flex items-center gap-3 mb-6 bg-slate-200/60 p-2 rounded border border-slate-300 text-xs">
                    <div class="bg-[#181c28] text-white text-xs font-bold px-3 py-1.5 rounded">[1] INPUT DATA</div>
                    <div class="text-slate-400 text-xs">====</div>
                    <div class="text-slate-500 text-xs px-3 py-1.5">[2] VERIFIKASI OTP</div>
                </div>

                <!-- Form Card -->
                <form id="registration-form">
                    <div class="border border-slate-200 bg-white p-4 shadow-sm mb-4">
                        <div class="bg-[#eef2f9] px-3 py-2 text-xs font-bold text-[#181c28] tracking-wider mb-4 flex justify-between items-center">
                            <span>[SEKSI 1. DATA PENDAFTARAN]</span>
                            <span class="text-slate-500 font-normal">[WAJIB]</span>
                        </div>

                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1" for="full-name">Nama Lengkap</label>
                        <input class="w-full bg-slate-50 border border-slate-300 text-sm px-3 py-2 text-[#181c28] focus:outline-none focus:border-[#181c28] mb-3" id="full-name" name="full-name" required type="text" value="Budi Santoso">

                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1" for="phone-number">Nomor WhatsApp / HP</label>
                        <div class="flex mb-3">
                            <span class="inline-flex items-center px-3 border border-r-0 border-slate-300 bg-slate-200 text-slate-700 text-sm font-bold">+62</span>
                            <input class="flex-1 bg-slate-50 border border-slate-300 text-sm px-3 py-2 text-[#181c28] focus:outline-none focus:border-[#181c28]" id="phone-number" name="phone-number" required type="tel" value="812-3456-7890">
                        </div>
                        <p class="text-[11px] text-slate-500 mb-3">*Kode verifikasi OTP 6-digit akan dikirim ke nomor ini.</p>

                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1" for="password">Kata Sandi Baru</label>
                        <div class="flex relative">
                            <input class="w-full bg-slate-50 border border-slate-300 text-sm px-3 py-2 text-[#181c28] focus:outline-none focus:border-[#181c28]" id="password" name="password" required type="password" value="rahasia123">
                            <button class="border border-l-0 border-slate-300 bg-slate-100 hover:bg-slate-200 px-3 text-xs text-slate-700 font-bold" id="toggle-pw-btn" type="button">[TAMPILKAN]</button>
                        </div>
                    </div>

                    <button class="w-full bg-[#181c28] hover:bg-slate-800 text-white font-bold text-sm py-3 px-4 shadow transition-colors mb-3 flex items-center justify-center" type="submit">
                        [LANJUT KE VERIFIKASI OTP →]
                    </button>

                    <div class="text-center">
                        <a href="login.php" class="text-xs text-slate-500 hover:text-[#181c28] font-medium hover:underline">[BATALKAN]</a>
                    </div>
                    <div class="text-center mt-4 text-xs text-slate-500">
                        <p>Sudah punya akun? <a href="login.php" class="font-bold text-[#181c28] hover:underline">[MASUK]</a></p>
                    </div>
                </form>
            </div>
        </main>
        <!-- END: MainContent -->
    </div>

    <script>
        const togglePwBtn = document.getElementById('toggle-pw-btn');
        const pwInput = document.getElementById('password');
        togglePwBtn.addEventListener('click', () => {
            const show = pwInput.type === 'password';
            pwInput.type = show ? 'text' : 'password';
            togglePwBtn.textContent = show ? '[SEMBUNYIKAN]' : '[TAMPILKAN]';
        });

        document.getElementById('registration-form').addEventListener('submit', (e) => {
            e.preventDefault();
            sessionStorage.setItem('yaraRegData', JSON.stringify({
                name: document.getElementById('full-name').value.trim(),
                phone: '+62 ' + document.getElementById('phone-number').value.trim()
            }));
            window.location.href = 'verifikasi-otp.php';
        });
    </script>
</body>

</html>