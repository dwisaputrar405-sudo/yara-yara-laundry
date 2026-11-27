<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
    <title>Lupa Kata Sandi - Yara Yara Laundry</title>
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
                        LUPA KATA SANDI
                    </h1>
                    <p class="text-xs text-slate-600 mt-1">
                        Masukkan nomor telepon terdaftar untuk menerima kode OTP pemulihan.
                    </p>
                </section>

                <section class="bg-[#F8FBFF] border-2 border-[#0F2854] p-4">
                    <form class="flex flex-col gap-4" id="reset-password-form" method="POST">
                        <div class="flex flex-col gap-1.5">
                            <label class="text-xs font-bold uppercase tracking-wide" for="phone-number">
                                NOMOR TELEPON / WHATSAPP [WAJIB]
                            </label>
                            <div class="flex items-stretch border-2 border-[#0F2854] bg-[#F8FBFF]">
                                <span class="px-3 py-2.5 bg-[#EEF4FA] border-r-2 border-[#0F2854] text-xs font-bold flex items-center select-none">
                                    +62
                                </span>
                                <input class="w-full px-3 py-2.5 text-sm bg-transparent border-0 focus:ring-0 focus:outline-none placeholder:text-slate-400" id="phone-number" inputmode="numeric" name="phone" placeholder="812-3456-7890" required type="tel" />
                            </div>
                            <p class="text-[11px] text-slate-500">
                                *Kode verifikasi OTP 6 digit akan dikirim ke nomor ini.
                            </p>
                        </div>

                        <button class="w-full bg-[#0F2854] text-white py-3.5 px-4 text-xs font-bold uppercase tracking-wider border-2 border-[#0F2854] hover:bg-[#1C4D8D] active:translate-y-0.5 transition-transform" type="submit">
                            [KIRIM KODE OTP PEMULIHAN]
                        </button>
                    </form>
                </section>

                <section class="bg-[#EEF4FA] border-2 border-[#0F2854] p-4 flex flex-col gap-3">
                    <div class="text-xs font-bold uppercase">
                        INGAT KATA SANDI ANDA?
                    </div>
                    <a class="w-full bg-[#F8FBFF] text-[#0F2854] py-3 px-4 text-xs font-bold uppercase tracking-wide border-2 border-dashed border-[#0F2854] text-center hover:bg-[#F5F9FE]" href="login.php">
                        [&lt;- KEMBALI KE HALAMAN LOGIN]
                    </a>
                </section>

                <footer class="bg-[#F8FBFF] border-2 border-[#0F2854] p-3.5 text-xs">
                    <div class="flex items-center gap-1.5 font-bold uppercase mb-1">
                        <span class="text-red-700">[!]</span>
                        <span>BANTUAN TEKNIS</span>
                    </div>
                    <p class="text-[11px] text-slate-700 leading-relaxed">
                        WhatsApp Admin: <strong class="text-[#0F2854]">+62 851-7999-8522</strong> (07.00 - 21.00 WIB).
                    </p>
                </footer>
            </div>
        </main>
    </div>

    <script>
        document.getElementById('reset-password-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            const phone = document.getElementById('phone-number').value.trim();
            const res = await fetch('../api/user.php?route=auth&action=request_otp', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ phone, purpose: 'RESET_PASSWORD' })
            });
            const data = await res.json();
            if (!res.ok || !data.success) return alert(data.error || 'Nomor tidak terdaftar.');

            sessionStorage.setItem('yaraResetPhone', data.phone);
            alert('Kode OTP telah dikirim ke WhatsApp ' + data.phone);
            window.location.href = 'otp-reset-pw.php';
        });
    </script>
</body>

</html>