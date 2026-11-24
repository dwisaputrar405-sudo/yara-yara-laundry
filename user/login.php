<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
    <title>Masuk Akun - Yara Yara Laundry</title>
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
        <main class="flex-1 p-4 py-8 flex flex-col" data-purpose="login-main-content">
            <div class="my-auto flex flex-col gap-4 w-full">
                <section class="bg-white border-2 border-[#181c28] p-4" data-purpose="page-header-card">
                    <h1 class="text-base font-bold tracking-tight uppercase">
                        MASUK KE AKUN PELANGGAN
                    </h1>
                    <p class="text-xs text-slate-600 mt-1">
                        Akses layanan Yara Yara Laundry.
                    </p>
                </section>

                <section class="bg-white border-2 border-[#181c28] p-4" data-purpose="login-form-card">
                    <form class="flex flex-col gap-4" id="customer-login-form">
                        <div class="flex flex-col gap-1.5" data-purpose="phone-input-group">
                            <label class="text-xs font-bold uppercase tracking-wide" for="phone-number">
                                NOMOR TELEPON [WAJIB]
                            </label>
                            <div class="flex items-stretch border-2 border-[#181c28] bg-white">
                                <span class="px-3 py-2.5 bg-[#f4f5f7] border-r-2 border-[#181c28] text-xs font-bold flex items-center select-none">
                                    +62
                                </span>
                                <input class="w-full px-3 py-2.5 text-sm bg-transparent border-0 focus:ring-0 focus:outline-none placeholder:text-slate-400" id="phone-number" inputmode="numeric" name="phone" placeholder="812-3456-7890" required type="tel" />
                            </div>
                        </div>

                        <div class="flex flex-col gap-1.5" data-purpose="password-input-group">
                            <label class="text-xs font-bold uppercase tracking-wide" for="password">
                                KATA SANDI [WAJIB]
                            </label>
                            <div class="flex items-stretch border-2 border-[#181c28] bg-white">
                                <input class="w-full px-3 py-2.5 text-sm bg-transparent border-0 focus:ring-0 focus:outline-none placeholder:text-slate-400" id="password" name="password" placeholder="••••••••" required type="password" />
                                <button class="px-3 py-2.5 bg-[#f4f5f7] border-l-2 border-[#181c28] text-[11px] font-bold uppercase tracking-tight hover:bg-slate-200 active:bg-slate-300 whitespace-nowrap" id="toggle-password-btn" type="button">
                                    [TAMPILKAN]
                                </button>
                            </div>
                            <div class="flex justify-end">
                                <a href="reset-pw.php" class="text-[11px] font-bold uppercase tracking-tight hover:underline">[LUPA KATA SANDI?]</a>
                            </div>
                        </div>

                        <button class="w-full bg-[#181c28] text-white py-3.5 px-4 text-xs font-bold uppercase tracking-wider border-2 border-[#181c28] hover:bg-slate-800 active:translate-y-0.5 transition-transform" type="submit">
                            [MASUK KE SISTEM LAUNDRY]
                        </button>
                    </form>
                </section>

                <section class="bg-[#f4f5f7] border-2 border-[#181c28] p-4 flex flex-col gap-3" data-purpose="register-prompt-card">
                    <div class="flex items-center gap-2 text-xs font-bold uppercase">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" stroke-linecap="square" stroke-linejoin="miter"></path>
                        </svg>
                        <span>BELUM PUNYA AKUN?</span>
                    </div>
                    <a class="w-full bg-white text-[#181c28] py-3 px-4 text-xs font-bold uppercase tracking-wide border-2 border-dashed border-[#181c28] text-center hover:bg-slate-50" href="register.php">
                        [DAFTAR AKUN BARU SEKARANG -&gt;]
                    </a>
                </section>

                <footer class="bg-white border-2 border-[#181c28] p-3.5 text-xs" data-purpose="operational-help-footer">
                    <div class="flex items-center gap-1.5 font-bold uppercase mb-1">
                        <span class="text-red-700">[!]</span>
                        <span>BANTUAN TEKNIS</span>
                    </div>
                    <p class="text-[11px] text-slate-700 leading-relaxed">
                        WhatsApp Admin: <strong class="text-[#181c28]">+62 811-0000-YYL</strong> (07.00 - 21.00 WIB).
                    </p>
                </footer>
            </div>
        </main>
        <!-- END: MainContent -->
    </div>

    <script>
        const toggleBtn = document.getElementById('toggle-password-btn');
        const passwordInput = document.getElementById('password');
        toggleBtn.addEventListener('click', () => {
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';
            toggleBtn.textContent = isPassword ? '[SEMBUNYIKAN]' : '[TAMPILKAN]';
        });

        document.getElementById('customer-login-form').addEventListener('submit', (e) => {
            e.preventDefault();
            window.location.href = 'index.php';
        });
    </script>
</body>

</html>