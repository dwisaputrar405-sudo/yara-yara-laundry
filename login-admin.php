<?php
session_start();
if (isset($_SESSION['user'])) {
  header('Location: index.php');
  exit;
}
?>
<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <title>Login Admin - Portal Admin</title>
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <style data-purpose="brutalist-base">
    body {
      font-family:
        ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas,
        "Liberation Mono", "Courier New", monospace;
    }
  </style>
</head>

<body
  class="bg-slate-100 text-slate-900 min-h-screen flex flex-col antialiased selection:bg-slate-900 selection:text-white">
  <!-- BEGIN: TopHeader -->
  <header class="bg-white border-b-2 border-slate-900 px-4 py-3 sticky top-0 z-10">
    <div class="max-w-4xl mx-auto flex items-center justify-between">
      <div class="flex items-center gap-2">
        <svg aria-hidden="true" class="w-5 h-5 text-slate-900 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-linecap="square" stroke-linejoin="miter" stroke-width="2"></path>
        </svg>
        <span class="font-bold text-sm tracking-wider uppercase">[KHUSUS ADMIN - YARA YARA LAUNDRY]</span>
      </div>
      <span class="text-xs text-slate-500 uppercase font-bold hidden sm:block">[SYS.VER 2.4]</span>
    </div>
  </header>
  <!-- END: TopHeader -->

  <!-- BEGIN: MainContent -->
  <main class="flex-1 w-full max-w-xl mx-auto p-6 md:p-8 flex flex-col justify-center">
    <section class="bg-white border-2 border-slate-900 p-6 md:p-8 shadow-sm" data-purpose="login-container">
      <div class="flex items-center justify-between border-b-2 border-slate-900 pb-3 mb-6">
        <div>
          <h1 class="text-sm md:text-base font-bold tracking-tight uppercase">[LOGIN STAF &amp; OWNER]</h1>
          <p class="text-xs text-slate-500 mt-1">Akses operasional kasir &amp; kontrol manajemen</p>
        </div>
      </div>
      <form action="#" class="flex flex-col gap-5" id="staff-login-form" method="POST">
        <div id="login-error-box" class="hidden bg-red-50 border-2 border-red-700 px-3 py-2 text-xs font-bold text-red-700"></div>

        <div data-purpose="email-field">
          <label class="block text-xs font-bold uppercase tracking-wide mb-1.5" for="email-input">EMAIL [WAJIB]</label>
          <div class="relative flex items-center">
            <span class="absolute left-3 text-slate-500 pointer-events-none">
              <svg aria-hidden="true" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" stroke-linecap="square" stroke-linejoin="miter" stroke-width="2"></path>
              </svg>
            </span>
            <input class="w-full pl-10 pr-3 py-2.5 text-sm bg-white border-2 border-slate-900 rounded-none focus:outline-none focus:ring-0 focus:border-slate-900 focus:bg-slate-50" id="email-input" name="email" placeholder="Masukkan Email" required type="email" />
          </div>
        </div>

        <div data-purpose="password-field">
          <div class="flex items-center justify-between mb-1.5">
            <label class="block text-xs font-bold uppercase tracking-wide" for="password">KATA SANDI [WAJIB]</label>
          </div>
          <div class="relative flex items-center">
            <span class="absolute left-3 text-slate-500 pointer-events-none">
              <svg aria-hidden="true" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" stroke-linecap="square" stroke-linejoin="miter" stroke-width="2"></path>
              </svg>
            </span>
            <input class="w-full pl-10 pr-28 py-2.5 text-sm bg-white border-2 border-slate-900 rounded-none focus:outline-none focus:ring-0 focus:border-slate-900 focus:bg-slate-50" id="password" minlength="6" name="password" placeholder="Masukkan Password" required type="password" />
            <button class="absolute right-1.5 px-2 py-1 text-[11px] font-bold uppercase bg-slate-100 border border-slate-900 hover:bg-slate-200 active:bg-slate-300" id="toggle-password-btn" type="button">[TAMPILKAN]</button>
          </div>
        </div>

        <div class="pt-2 border-t border-slate-200">
          <button id="login-submit-btn" class="w-full bg-slate-900 text-white font-bold text-xs uppercase tracking-wider py-3.5 px-4 border-2 border-slate-900 hover:bg-slate-800 active:bg-black transition-colors flex items-center justify-center gap-2" type="submit">
            <span>[ MASUK KE DASHBOARD → ]</span>
          </button>
          <p class="text-[10px] text-slate-500 mt-2 text-center uppercase tracking-wide">*Hubungi Operator Jika Terjadi Kendala</p>
        </div>
      </form>
    </section>
  </main>

  <script>
    const passInput = document.getElementById("password");
    const toggleBtn = document.getElementById("toggle-password-btn");
    toggleBtn.addEventListener("click", () => {
      const show = passInput.type === "password";
      passInput.type = show ? "text" : "password";
      toggleBtn.textContent = show ? "[SEMBUNYIKAN]" : "[TAMPILKAN]";
    });

    document.getElementById("staff-login-form").addEventListener("submit", async (e) => {
      e.preventDefault();
      const errBox = document.getElementById("login-error-box");
      const btn = document.getElementById("login-submit-btn");
      errBox.classList.add("hidden");
      btn.disabled = true;

      try {
        const res = await fetch("api/index.php?route=auth&action=login", {
          method: "POST",
          headers: {
            "Content-Type": "application/json"
          },
          body: JSON.stringify({
            email: document.getElementById("email-input").value,
            password: passInput.value
          })
        });
        const data = await res.json();
        if (res.ok && data.success) {
          window.location.href = "index.php";
        } else {
          errBox.textContent = `[GAGAL] ${data.error || "Email atau kata sandi salah."}`;
          errBox.classList.remove("hidden");
        }
      } catch {
        errBox.textContent = "[ERROR] Gagal terhubung ke server PHP/MySQL.";
        errBox.classList.remove("hidden");
      } finally {
        btn.disabled = false;
      }
    });
  </script>
</body>

</html>