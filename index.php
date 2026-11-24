<?php
session_start();
if (!isset($_SESSION['user'])) {
  header('Location: login-admin.php');
  exit;
}

$u         = $_SESSION['user'];
$isOwner   = ($u['role'] === 'owner');
$roleLabel = $isOwner ? 'OWNER' : 'KASIR / ADMIN';

$payMethods = ['QRIS', 'Tunai', 'Transfer'];
$steps = [
  0 => '[0/7] MENUNGGU JEMPUT KURIR',
  1 => '[1/7] BUTUH TIMBANG',
  2 => '[2/7] ANTRE CUCI',
  3 => '[3/7] CUCI',
  4 => '[4/7] KERING',
  5 => '[5/7] SETRIKA',
  6 => '[6/7] SIAP DIAMBIL / DIANTAR',
  7 => '[7/7] SELESAI / DISERAHKAN',
];
?>
<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <title>[ADMIN YARA LAUNDRY] - Dashboard Desktop</title>
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <link href="https://fonts.googleapis.com" rel="preconnect" />
  <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
  <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet" />
  <style>
    body {
      font-family: "JetBrains Mono", ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
      background-color: #f4f6f9;
      color: #1e2430;
    }
  </style>
</head>

<body data-user-role="<?= htmlspecialchars($u['role']) ?>" class="min-h-screen flex flex-col overflow-x-hidden antialiased selection:bg-[#1e2430] selection:text-white">
  <!-- TopHeader -->
  <header class="bg-white border-b-2 border-[#1e2430] sticky top-0 z-30">
    <div class="px-4 md:px-6 h-16 flex items-center justify-between gap-2">
      <div class="flex items-center gap-3 md:gap-4 min-w-0">
        <div class="w-9 h-9 bg-[#1e2430] text-white flex items-center justify-center font-bold text-sm tracking-tighter shrink-0">YL</div>
        <div class="min-w-0">
          <h1 class="text-sm md:text-base font-extrabold tracking-tight text-[#1e2430] leading-none truncate">[ADMIN YARA LAUNDRY]</h1>
          <p class="text-[10px] md:text-[11px] font-semibold text-slate-500 mt-1 flex items-center gap-1.5 truncate">
            <span class="inline-block w-2 h-2 bg-emerald-600 shrink-0"></span>
            <span class="truncate"><?= htmlspecialchars($u['code']) ?> TERVERIFIKASI: <?= $roleLabel ?></span>
          </p>
        </div>
      </div>
      <div class="flex items-center gap-2 md:gap-3 relative shrink-0">
        <button id="btn-notif" aria-label="Notifikasi" class="relative p-2 border border-slate-300 hover:border-[#1e2430] bg-white transition-colors" type="button">
          <svg class="w-5 h-5 text-[#1e2430]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" stroke-linecap="square" stroke-linejoin="miter"></path>
          </svg>
          <span id="notif-dot" class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-600"></span>
        </button>
        <div id="notif-popover" class="hidden absolute right-0 top-12 w-80 max-w-[calc(100vw-2rem)] bg-white border-2 border-[#1e2430] p-3 z-40">
          <div class="flex items-center justify-between pb-2 border-b border-slate-300 text-xs font-bold">
            <span>// NOTIFIKASI KENDALA</span>
            <button id="btn-close-notif" type="button" class="text-slate-500 hover:text-[#1e2430]">[X]</button>
          </div>
          <div id="notif-list" class="divide-y divide-slate-200 text-xs max-h-60 overflow-y-auto mt-1"></div>
        </div>

        <button id="btn-profile" aria-label="Profil Kasir" class="flex items-center gap-2 md:gap-2.5 pl-2 pr-2.5 md:pr-3 py-1.5 border border-[#1e2430] bg-[#1e2430] text-white hover:bg-slate-800 transition-colors" type="button">
          <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-linecap="square" stroke-linejoin="miter"></path>
          </svg>
          <span class="text-xs font-bold tracking-wide whitespace-nowrap"><?= htmlspecialchars($u['short_name']) ?> [<?= $roleLabel ?>]</span>
        </button>

        <div id="profile-popover" class="hidden absolute right-0 top-12 w-80 max-w-[calc(100vw-2rem)] bg-white border-2 border-[#1e2430] p-3.5 z-40 text-xs space-y-2.5">
          <div class="flex items-center justify-between pb-2 border-b border-slate-300 font-bold">
            <span>// VERIFIKASI AKUN AKTIF</span>
            <button id="btn-close-profile" type="button" class="text-slate-500 hover:text-[#1e2430]">[X]</button>
          </div>
          <div class="space-y-1 text-[11px]">
            <div><span class="text-slate-500">NAMA:</span> <strong><?= htmlspecialchars($u['name']) ?></strong></div>
            <div><span class="text-slate-500">EMAIL:</span> <strong><?= htmlspecialchars($u['email']) ?></strong></div>
            <div><span class="text-slate-500">ROLE:</span> <strong class="px-1.5 py-0.5 bg-[#1e2430] text-white"><?= $roleLabel ?></strong></div>
            <div><span class="text-slate-500">LOGIN:</span> <span><?= htmlspecialchars($u['login_time']) ?></span></div>
          </div>
          <?php if ($isOwner): ?>
            <button type="button" onclick="openServicesModal()" class="w-full py-2 bg-white hover:bg-slate-100 text-[#1e2430] font-bold border border-[#1e2430]">[KELOLA LAYANAN &amp; HARGA]</button>
          <?php endif; ?>
          <button type="button" onclick="logoutUser()" class="w-full py-2 bg-red-700 hover:bg-red-800 text-white font-bold border border-red-700">[ LOGOUT / KELUAR ]</button>
        </div>
      </div>
    </div>

    <!-- Navigasi Horizontal Khusus Layar Tablet Split-Screen / Sempit (< md) -->
    <nav aria-label="Navigasi Layar Sempit" class="md:hidden bg-slate-50 border-t border-slate-300 px-4 py-2 flex items-center gap-1.5 overflow-x-auto">
      <a data-nav="RINGKASAN" class="nav-item shrink-0 flex items-center gap-1.5 px-2.5 py-1.5 bg-[#1e2430] text-white text-[11px] font-bold tracking-wide border border-[#1e2430]" href="#">
        <span>[RINGKASAN]</span>
      </a>
      <a data-nav="PESANAN" class="nav-item shrink-0 flex items-center gap-1.5 px-2.5 py-1.5 bg-white text-[#1e2430] hover:bg-slate-100 text-[11px] font-semibold tracking-wide border border-slate-300 transition-colors" href="#">
        <span>[PESANAN]</span>
      </a>
      <a data-nav="TIMBANG" class="nav-item shrink-0 flex items-center gap-1.5 px-2.5 py-1.5 bg-white text-[#1e2430] hover:bg-slate-100 text-[11px] font-semibold tracking-wide border border-slate-300 transition-colors" href="#">
        <span>[TIMBANG/UPDATE]</span>
      </a>
      <?php if ($isOwner): ?>
        <a data-nav="LAYANAN" class="nav-item shrink-0 flex items-center gap-1.5 px-2.5 py-1.5 bg-white text-[#1e2430] hover:bg-slate-100 text-[11px] font-semibold tracking-wide border border-slate-300 transition-colors" href="#">
          <span>[LAYANAN &amp; HARGA]</span>
        </a>
      <?php endif; ?>
      <a data-nav="AKUN" class="nav-item shrink-0 flex items-center gap-1.5 px-2.5 py-1.5 bg-white text-[#1e2430] hover:bg-slate-100 text-[11px] font-semibold tracking-wide border border-slate-300 transition-colors" href="#">
        <span>[AKUN]</span>
      </a>
    </nav>
  </header>

  <div class="flex-1 flex min-w-0">
    <!-- SidebarNavigation (Tablet md+ & PC lg+) -->
    <aside class="w-52 lg:w-60 bg-white border-r-2 border-[#1e2430] shrink-0 hidden md:flex flex-col justify-between">
      <nav aria-label="Navigasi Utama" class="p-3 lg:p-4 space-y-1.5 sticky top-16">
        <p class="px-3 pb-2 text-[10px] font-bold text-slate-400 tracking-widest uppercase">MENU UTAMA</p>
        <a data-nav="RINGKASAN" class="nav-item flex items-center gap-2.5 lg:gap-3 px-3 py-2.5 bg-[#1e2430] text-white text-xs font-bold tracking-wide border border-[#1e2430]" href="#">
          <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" stroke-linecap="square" stroke-linejoin="miter"></path>
          </svg>
          <span>[RINGKASAN]</span>
        </a>
        <a data-nav="PESANAN" class="nav-item flex items-center gap-2.5 lg:gap-3 px-3 py-2.5 bg-white text-[#1e2430] hover:bg-slate-100 text-xs font-semibold tracking-wide border border-transparent hover:border-slate-300 transition-colors" href="#">
          <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" stroke-linecap="square" stroke-linejoin="miter"></path>
          </svg>
          <span>[PESANAN]</span>
        </a>
        <a data-nav="TIMBANG" class="nav-item flex items-center gap-2.5 lg:gap-3 px-3 py-2.5 bg-white text-[#1e2430] hover:bg-slate-100 text-xs font-semibold tracking-wide border border-transparent hover:border-slate-300 transition-colors" href="#">
          <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" stroke-linecap="square" stroke-linejoin="miter"></path>
          </svg>
          <span>[TIMBANG/UPDATE]</span>
        </a>
        <?php if ($isOwner): ?>
          <a data-nav="LAYANAN" class="nav-item flex items-center gap-2.5 lg:gap-3 px-3 py-2.5 bg-white text-[#1e2430] hover:bg-slate-100 text-xs font-semibold tracking-wide border border-transparent hover:border-slate-300 transition-colors" href="#">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" stroke-linecap="square" stroke-linejoin="miter"></path>
            </svg>
            <span>[LAYANAN &amp; HARGA]</span>
          </a>
        <?php endif; ?>
        <a data-nav="AKUN" class="nav-item flex items-center gap-2.5 lg:gap-3 px-3 py-2.5 bg-white text-[#1e2430] hover:bg-slate-100 text-xs font-semibold tracking-wide border border-transparent hover:border-slate-300 transition-colors" href="#">
          <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" stroke-linecap="square" stroke-linejoin="miter"></path>
            <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke-linecap="square" stroke-linejoin="miter"></path>
          </svg>
          <span>[AKUN]</span>
        </a>
      </nav>
      <div class="p-3 lg:p-4 border-t border-slate-200 text-[11px] text-slate-500 space-y-2">
        <div class="flex items-center justify-between">
          <span>STATUS SISTEM</span>
          <span id="system-status-badge" class="font-bold text-emerald-700">[ONLINE]</span>
        </div>
        <button type="button" onclick="logoutUser()" class="w-full py-1.5 bg-white hover:bg-red-50 text-red-700 border border-red-700 font-bold text-[11px]">[LOGOUT]</button>
      </div>
    </aside>

    <!-- MainContent -->
    <main class="flex-1 min-w-0 p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto w-full space-y-6">
      <section class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-5 border-b border-slate-300">
        <div class="min-w-0">
          <h2 class="text-lg sm:text-xl font-extrabold tracking-tight text-[#1e2430] break-words">HALO, <?= htmlspecialchars($u['name']) ?> (<?= htmlspecialchars($u['short_name']) ?>)</h2>
          <div class="mt-1.5 flex flex-wrap items-center gap-2">
            <div class="inline-flex items-center gap-2 px-2.5 py-1 bg-slate-200/70 border border-slate-300 text-xs font-medium text-slate-700">
              <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="square" stroke-linejoin="miter"></path>
              </svg>
              <span id="live-clock">MEMUAT WAKTU WIB...</span>
            </div>
            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-white border border-[#1e2430] text-[11px] font-bold text-[#1e2430] max-w-full">
              <span class="w-2 h-2 bg-emerald-600 shrink-0"></span>
              <span class="truncate">AKUN: <?= htmlspecialchars($u['email']) ?> [<?= $roleLabel ?>]</span>
            </div>
          </div>
        </div>
        <div class="flex flex-wrap items-center gap-2 shrink-0">
          <?php if ($isOwner): ?>
            <button type="button" onclick="openServicesModal()" class="px-3.5 lg:px-4 py-2.5 lg:py-3 bg-white hover:bg-slate-100 text-[#1e2430] text-xs font-bold tracking-wider border-2 border-[#1e2430] transition-colors whitespace-nowrap">[ KELOLA LAYANAN &amp; HARGA ]</button>
          <?php endif; ?>
          <button id="btn-new-order" class="px-4 lg:px-5 py-2.5 lg:py-3 bg-[#1e2430] hover:bg-slate-800 text-white text-xs font-bold tracking-wider border-2 border-[#1e2430] transition-colors whitespace-nowrap" type="button">[ + TERIMA CUCIAN BARU ]</button>
        </div>
      </section>

      <!-- Summary Cards -->
      <section class="space-y-3">
        <div class="flex items-center justify-between text-xs font-bold tracking-wider text-slate-500 uppercase">
          <span>// REKAP STATUS TIKET</span>
          <span id="summary-total-unit">TOTAL: 0 UNIT</span>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-4">
          <div class="bg-white border-2 border-[#1e2430] p-4 flex flex-col justify-between min-w-0">
            <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wide">Aktif di Outlet</span>
            <div class="mt-2 flex items-baseline justify-between gap-2">
              <span id="stat-active" class="text-2xl lg:text-3xl font-extrabold text-[#1e2430]">00</span>
              <span class="text-[11px] font-semibold text-slate-400">TIKET</span>
            </div>
          </div>
          <div class="bg-white border-2 border-red-700 p-4 flex flex-col justify-between min-w-0">
            <span class="text-[11px] font-bold text-red-700 uppercase tracking-wide">Jemput / Butuh Timbang</span>
            <div class="mt-2 flex items-baseline justify-between gap-2">
              <span id="stat-weighing" class="text-2xl lg:text-3xl font-extrabold text-red-700">00</span>
              <span class="text-[11px] font-semibold text-red-700">TIKET</span>
            </div>
          </div>
          <div class="bg-white border-2 border-[#1e2430] p-4 flex flex-col justify-between min-w-0">
            <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wide">Cuci / Kering / Setrika</span>
            <div class="mt-2 flex items-baseline justify-between gap-2">
              <span id="stat-process" class="text-2xl lg:text-3xl font-extrabold text-[#1e2430]">00</span>
              <span class="text-[11px] font-semibold text-slate-400">PROSES</span>
            </div>
          </div>
          <div class="bg-white border-2 border-[#1e2430] p-4 flex flex-col justify-between min-w-0">
            <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wide">Siap Diambil / Diantar</span>
            <div class="mt-2 flex items-baseline justify-between gap-2">
              <span id="stat-ready" class="text-2xl lg:text-3xl font-extrabold text-[#1e2430]">00</span>
              <span class="text-[11px] font-semibold text-slate-400">DI RAK</span>
            </div>
          </div>
        </div>
      </section>

      <!-- Delay Warning -->
      <section id="delay-warning-banner" class="hidden bg-red-50 border border-red-300 px-4 py-2.5 flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-xs">
        <div class="flex items-center gap-2">
          <span class="px-1.5 py-0.5 bg-red-700 text-white text-[10px] font-bold shrink-0">PERINGATAN</span>
          <p id="delay-warning-text" class="text-[#1e2430]"></p>
        </div>
        <a id="btn-review-overdue" class="font-bold text-red-700 hover:underline text-[11px] whitespace-nowrap" href="#">[TINJAU KENDALA →]</a>
      </section>

      <!-- Orders Table -->
      <section id="orders-section" class="space-y-4 pt-2">
        <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-3 bg-white p-3.5 lg:p-4 border border-slate-300">
          <div class="relative w-full xl:flex-1 xl:max-w-md">
            <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-linecap="square" stroke-linejoin="miter"></path>
            </svg>
            <input id="search-input" class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-300 focus:border-[#1e2430] focus:ring-0 text-xs font-medium text-[#1e2430] placeholder:text-slate-400" placeholder="Cari no resi, nama, wa, atau alamat..." type="text" />
          </div>
          <div class="flex flex-wrap items-center gap-1.5 lg:gap-2">
            <?php foreach (['ALL' => 'SEMUA', 'BARU' => 'BARU', 'ANTAR_JEMPUT' => 'ANTAR-JEMPUT', 'DICUCI' => 'DICUCI', 'SIAP' => 'SIAP'] as $k => $lbl): ?>
              <button data-filter="<?= $k ?>" class="filter-btn px-2.5 lg:px-3 py-1.5 lg:py-2 bg-white text-[#1e2430] text-xs font-semibold border border-slate-300 whitespace-nowrap" type="button"><?= $lbl ?></button>
            <?php endforeach; ?>
          </div>
        </div>

        <div class="bg-white border-2 border-[#1e2430] w-full overflow-x-auto">
          <table class="w-full min-w-[820px] text-left text-xs border-collapse">
            <thead class="bg-slate-100 border-b-2 border-[#1e2430] text-[11px] font-bold text-[#1e2430] tracking-wider uppercase">
              <tr>
                <th class="p-3 lg:p-4 border-r border-slate-300 whitespace-nowrap">NO RESI</th>
                <th class="p-3 lg:p-4 border-r border-slate-300 whitespace-nowrap">WAKTU</th>
                <th class="p-3 lg:p-4 border-r border-slate-300 whitespace-nowrap">PELANGGAN &amp; METODE</th>
                <th class="p-3 lg:p-4 border-r border-slate-300 whitespace-nowrap">LAYANAN &amp; BERAT</th>
                <th class="p-3 lg:p-4 border-r border-slate-300 whitespace-nowrap">STATUS PROSES</th>
                <th class="p-3 lg:p-4 border-r border-slate-300 whitespace-nowrap">PEMBAYARAN</th>
                <th class="p-3 lg:p-4 text-center whitespace-nowrap">AKSI</th>
              </tr>
            </thead>
            <tbody id="orders-tbody" class="divide-y border-slate-300"></tbody>
          </table>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-2 text-xs">
          <p id="pagination-info" class="text-slate-500 font-medium"></p>
          <div id="pagination-controls" class="flex flex-wrap items-center gap-1.5"></div>
        </div>
      </section>
    </main>
  </div>

  <!-- MODAL 1: CREATE TIKET BARU -->
  <div id="modal-create" class="hidden fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white border-2 border-[#1e2430] w-full max-w-xl my-auto">
      <div class="px-5 py-3.5 bg-[#1e2430] text-white flex items-center justify-between">
        <h3 class="text-xs font-extrabold tracking-wider uppercase">[ + TERIMA CUCIAN BARU // INPUT TIKET ]</h3>
        <button type="button" data-close-modal="modal-create" class="text-xs font-bold hover:text-slate-300">[X]</button>
      </div>
      <form id="form-create-order" class="p-4 sm:p-5 space-y-4 text-xs">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block font-bold uppercase text-[11px] mb-1">NAMA PELANGGAN *</label>
            <input name="customer_name" required placeholder="Contoh: Siti Rahma" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 focus:border-[#1e2430] focus:ring-0 text-xs" type="text" />
          </div>
          <div>
            <label class="block font-bold uppercase text-[11px] mb-1">NO WHATSAPP *</label>
            <input name="customer_phone" required placeholder="Contoh: +62 813-9821-4401" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 focus:border-[#1e2430] focus:ring-0 text-xs" type="text" />
          </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block font-bold uppercase text-[11px] mb-1">METODE PENGIRIMAN *</label>
            <select id="create-delivery-type" name="delivery_type" class="w-full px-3 py-2 bg-slate-50 border-2 border-[#1e2430] focus:ring-0 text-xs font-bold">
              <option value="DROP_OFF">DROP-OFF (Antar ke Outlet)</option>
              <option value="ANTAR_JEMPUT">ANTAR-JEMPUT (Kurir Jemput &amp; Antar)</option>
            </select>
          </div>
          <div>
            <label class="block font-bold uppercase text-[11px] mb-1">BIAYA ONGKIR (RP)</label>
            <input id="create-delivery-fee" name="delivery_fee" value="0" readonly class="w-full px-3 py-2 bg-slate-100 border border-slate-300 focus:ring-0 text-xs font-bold" type="number" />
          </div>
        </div>
        <div id="create-address-wrapper" class="hidden">
          <label class="block font-bold uppercase text-[11px] mb-1">ALAMAT PENJEMPUTAN &amp; PENGANTARAN *</label>
          <input id="create-pickup-address" name="pickup_address" placeholder="Contoh: Jl. Melati No. 12, Blok A" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 focus:border-[#1e2430] focus:ring-0 text-xs" type="text" />
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
          <div class="sm:col-span-2">
            <label class="block font-bold uppercase text-[11px] mb-1">JENIS LAYANAN *</label>
            <select id="create-service-select" name="service_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 focus:border-[#1e2430] focus:ring-0 text-xs font-semibold"></select>
          </div>
          <div>
            <label class="block font-bold uppercase text-[11px] mb-1">BERAT / QTY *</label>
            <input id="create-weight-input" name="weight" step="0.1" min="0.1" value="3.0" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 focus:border-[#1e2430] focus:ring-0 text-xs font-bold" type="number" />
          </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block font-bold uppercase text-[11px] mb-1">STATUS TIMBANGAN</label>
            <select name="is_estimated_weight" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 focus:border-[#1e2430] focus:ring-0 text-xs">
              <option value="1">ESTIMASI (Butuh Timbang Ulang)</option>
              <option value="0">AKTUAL (Sudah Ditimbang Kasir)</option>
            </select>
          </div>
          <div>
            <label class="block font-bold uppercase text-[11px] mb-1">TAHAP AWAL</label>
            <select id="create-status-step" name="status_step" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 focus:border-[#1e2430] focus:ring-0 text-xs">
              <option value="1">[1/7] BUTUH TIMBANG</option>
              <option value="0">[0/7] MENUNGGU JEMPUT KURIR</option>
              <option value="3">[3/7] LANGSUNG CUCI</option>
            </select>
          </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
          <div>
            <label class="block font-bold uppercase text-[11px] mb-1">PEMBAYARAN</label>
            <select name="payment_status" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 focus:border-[#1e2430] focus:ring-0 text-xs">
              <option value="BELUM">BELUM LUNAS</option>
              <option value="LUNAS">LUNAS</option>
            </select>
          </div>
          <div>
            <label class="block font-bold uppercase text-[11px] mb-1">METODE BAYAR</label>
            <select name="payment_method" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 focus:border-[#1e2430] focus:ring-0 text-xs">
              <?php foreach ($payMethods as $pm): ?><option value="<?= $pm ?>"><?= $pm ?></option><?php endforeach; ?>
            </select>
          </div>
          <div>
            <label class="block font-bold uppercase text-[11px] mb-1">TOTAL TAGIHAN (RP)</label>
            <input id="create-total-input" name="total_price" readonly class="w-full px-3 py-2 bg-slate-100 border border-[#1e2430] focus:ring-0 text-xs font-extrabold" type="number" />
          </div>
        </div>
        <div>
          <label class="block font-bold uppercase text-[11px] mb-1">CATATAN KHUSUS / MESIN</label>
          <input name="notes" placeholder="Contoh: Pisahkan baju putih, parfum lavender..." class="w-full px-3 py-2 bg-slate-50 border border-slate-300 focus:border-[#1e2430] focus:ring-0 text-xs" type="text" />
        </div>
        <div class="pt-3 border-t border-slate-300 flex items-center justify-end gap-2">
          <button type="button" data-close-modal="modal-create" class="px-4 py-2 bg-white border border-slate-300 hover:border-[#1e2430] font-bold">[BATAL]</button>
          <button type="submit" class="px-5 py-2 bg-[#1e2430] hover:bg-slate-800 text-white font-bold border border-[#1e2430]">[SIMPAN TIKET BARU]</button>
        </div>
      </form>
    </div>
  </div>

  <!-- MODAL 2: TIMBANG ULANG -->
  <div id="modal-weigh" class="hidden fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white border-2 border-[#1e2430] w-full max-w-md my-auto">
      <div class="px-5 py-3.5 bg-[#1e2430] text-white flex items-center justify-between">
        <h3 id="weigh-modal-title" class="text-xs font-extrabold tracking-wider uppercase">[TIMBANG ULANG TIKET]</h3>
        <button type="button" data-close-modal="modal-weigh" class="text-xs font-bold hover:text-slate-300">[X]</button>
      </div>
      <form id="form-weigh-order" class="p-4 sm:p-5 space-y-4 text-xs">
        <input type="hidden" id="weigh-order-id" />
        <input type="hidden" id="weigh-price-per-unit" />
        <input type="hidden" id="weigh-delivery-fee" value="0" />
        <div class="p-3 bg-slate-100 border border-slate-300 space-y-0.5">
          <div class="font-bold text-[#1e2430]" id="weigh-customer-info">-</div>
          <div class="text-[11px] text-slate-600" id="weigh-service-info">-</div>
          <div class="text-[11px] font-bold text-slate-700" id="weigh-delivery-info">-</div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block font-bold uppercase text-[11px] mb-1">BERAT AKTUAL (KG/PCS) *</label>
            <input id="weigh-actual-input" step="0.1" min="0.1" required class="w-full px-3 py-2 bg-slate-50 border-2 border-[#1e2430] focus:ring-0 text-sm font-extrabold" type="number" />
          </div>
          <div>
            <label class="block font-bold uppercase text-[11px] mb-1">TOTAL + ONGKIR (RP)</label>
            <input id="weigh-total-input" readonly class="w-full px-3 py-2 bg-slate-100 border border-slate-300 focus:ring-0 text-sm font-extrabold" type="number" />
          </div>
        </div>
        <div>
          <label class="block font-bold uppercase text-[11px] mb-1">ALOKASI MESIN / KETERANGAN PROSES</label>
          <input id="weigh-note-input" value="Drum-A1 (ETA 17:00)" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 focus:border-[#1e2430] focus:ring-0 text-xs" type="text" />
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block font-bold uppercase text-[11px] mb-1">STATUS BAYAR</label>
            <select id="weigh-pay-status" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 focus:border-[#1e2430] focus:ring-0 text-xs">
              <option value="BELUM">BELUM</option>
              <option value="LUNAS">LUNAS</option>
            </select>
          </div>
          <div>
            <label class="block font-bold uppercase text-[11px] mb-1">METODE</label>
            <select id="weigh-pay-method" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 focus:border-[#1e2430] focus:ring-0 text-xs">
              <?php foreach ($payMethods as $pm): ?><option value="<?= $pm ?>"><?= $pm ?></option><?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="pt-3 border-t border-slate-300 flex items-center justify-end gap-2">
          <button type="button" data-close-modal="modal-weigh" class="px-4 py-2 bg-white border border-slate-300 hover:border-[#1e2430] font-bold">[BATAL]</button>
          <button type="submit" class="px-4 py-2 bg-[#1e2430] hover:bg-slate-800 text-white font-bold border border-[#1e2430]">[KONFIRMASI &amp; MULAI CUCI]</button>
        </div>
      </form>
    </div>
  </div>

  <!-- MODAL 3: DETAIL, EDIT & DELETE -->
  <div id="modal-detail" class="hidden fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white border-2 border-[#1e2430] w-full max-w-2xl my-auto">
      <div class="px-5 py-3.5 bg-[#1e2430] text-white flex items-center justify-between">
        <h3 id="detail-modal-title" class="text-xs font-extrabold tracking-wider uppercase">[DETAIL &amp; UPDATE TIKET]</h3>
        <button type="button" data-close-modal="modal-detail" class="text-xs font-bold hover:text-slate-300">[X]</button>
      </div>
      <form id="form-edit-order" class="p-4 sm:p-5 space-y-4 text-xs">
        <input type="hidden" id="edit-order-id" />
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block font-bold uppercase text-[11px] mb-1">NAMA PELANGGAN</label>
            <input id="edit-customer-name" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 focus:border-[#1e2430] focus:ring-0 text-xs font-bold" type="text" />
          </div>
          <div>
            <label class="block font-bold uppercase text-[11px] mb-1">NO WHATSAPP</label>
            <input id="edit-customer-phone" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 focus:border-[#1e2430] focus:ring-0 text-xs" type="text" />
          </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
          <div>
            <label class="block font-bold uppercase text-[11px] mb-1">METODE KIRIM</label>
            <select id="edit-delivery-type" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 focus:border-[#1e2430] focus:ring-0 text-xs font-bold">
              <option value="DROP_OFF">DROP-OFF</option>
              <option value="ANTAR_JEMPUT">ANTAR-JEMPUT</option>
            </select>
          </div>
          <div>
            <label class="block font-bold uppercase text-[11px] mb-1">ONGKIR (RP)</label>
            <input id="edit-delivery-fee" min="0" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 focus:border-[#1e2430] focus:ring-0 text-xs font-bold" type="number" />
          </div>
          <div>
            <label class="block font-bold uppercase text-[11px] mb-1">ALAMAT JEMPUT/ANTAR</label>
            <input id="edit-pickup-address" placeholder="Kosong jika Drop-Off" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 focus:border-[#1e2430] focus:ring-0 text-xs" type="text" />
          </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
          <div>
            <label class="block font-bold uppercase text-[11px] mb-1">LAYANAN</label>
            <select id="edit-service-select" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 focus:border-[#1e2430] focus:ring-0 text-xs"></select>
          </div>
          <div>
            <label class="block font-bold uppercase text-[11px] mb-1">BERAT / QTY</label>
            <input id="edit-weight" step="0.1" min="0.1" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 focus:border-[#1e2430] focus:ring-0 text-xs font-bold" type="number" />
          </div>
          <div>
            <label class="block font-bold uppercase text-[11px] mb-1">TOTAL TAGIHAN (RP)</label>
            <input id="edit-total-price" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 focus:border-[#1e2430] focus:ring-0 text-xs font-extrabold" type="number" />
          </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block font-bold uppercase text-[11px] mb-1">TAHAP PROSES LAUNDRY</label>
            <select id="edit-status-step" class="w-full px-3 py-2 bg-slate-50 border-2 border-[#1e2430] focus:ring-0 text-xs font-bold">
              <?php foreach ($steps as $val => $lbl): ?><option value="<?= $val ?>"><?= $lbl ?></option><?php endforeach; ?>
            </select>
          </div>
          <div>
            <label class="block font-bold uppercase text-[11px] mb-1">LOKASI MESIN / RAK / KURIR</label>
            <input id="edit-status-note" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 focus:border-[#1e2430] focus:ring-0 text-xs" type="text" />
          </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
          <div>
            <label class="block font-bold uppercase text-[11px] mb-1">STATUS PEMBAYARAN</label>
            <select id="edit-payment-status" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 focus:border-[#1e2430] focus:ring-0 text-xs font-bold">
              <option value="BELUM">BELUM</option>
              <option value="LUNAS">LUNAS</option>
            </select>
          </div>
          <div>
            <label class="block font-bold uppercase text-[11px] mb-1">METODE PEMBAYARAN</label>
            <select id="edit-payment-method" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 focus:border-[#1e2430] focus:ring-0 text-xs">
              <?php foreach ($payMethods as $pm): ?><option value="<?= $pm ?>"><?= $pm ?></option><?php endforeach; ?>
            </select>
          </div>
          <div>
            <label class="block font-bold uppercase text-[11px] mb-1">STATUS KETERLAMBATAN</label>
            <select id="edit-is-overdue" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 focus:border-[#1e2430] focus:ring-0 text-xs">
              <option value="0">NORMAL (Sesuai Target)</option>
              <option value="1">LEWAT TARGET (Peringatan)</option>
            </select>
          </div>
        </div>
        <div>
          <label class="block font-bold uppercase text-[11px] mb-1">CATATAN ORDER</label>
          <input id="edit-notes" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 focus:border-[#1e2430] focus:ring-0 text-xs" type="text" />
        </div>
        <div class="bg-slate-50 border border-slate-300 p-3">
          <div class="text-[10px] font-bold text-slate-500 uppercase mb-1.5">// RIWAYAT LOG AKTIVITAS MYSQL</div>
          <ul id="edit-order-logs" class="space-y-1 text-[11px] text-slate-700 max-h-24 overflow-y-auto"></ul>
        </div>
        <div class="pt-3 border-t border-slate-300 flex flex-wrap items-center justify-between gap-2">
          <div class="flex items-center gap-2">
            <button id="btn-delete-trigger" type="button" class="px-3 py-2 bg-white border border-red-700 text-red-700 hover:bg-red-50 font-bold">[HAPUS TIKET]</button>
            <button id="btn-delete-confirm" type="button" class="hidden px-3 py-2 bg-red-700 text-white hover:bg-red-800 font-bold border border-red-700">[KONFIRMASI HAPUS PERMANEN]</button>
          </div>
          <div class="flex items-center gap-2">
            <button type="button" data-close-modal="modal-detail" class="px-4 py-2 bg-white border border-slate-300 hover:border-[#1e2430] font-bold">[TUTUP]</button>
            <button type="submit" class="px-5 py-2 bg-[#1e2430] hover:bg-slate-800 text-white font-bold border border-[#1e2430]">[SIMPAN PERUBAHAN]</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- MODAL 4: SERAHKAN CUCIAN -->
  <div id="modal-handover" class="hidden fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white border-2 border-[#1e2430] w-full max-w-md my-auto">
      <div class="px-5 py-3.5 bg-[#1e2430] text-white flex items-center justify-between">
        <h3 id="handover-modal-title" class="text-xs font-extrabold tracking-wider uppercase">[SERAH TERIMA CUCIAN KE PELANGGAN]</h3>
        <button type="button" data-close-modal="modal-handover" class="text-xs font-bold hover:text-slate-300">[X]</button>
      </div>
      <form id="form-handover-order" class="p-4 sm:p-5 space-y-4 text-xs">
        <input type="hidden" id="handover-order-id" />
        <input type="hidden" id="handover-delivery-type" />
        <div class="p-3 bg-slate-100 border border-slate-300 space-y-1">
          <div id="handover-summary" class="font-bold text-[#1e2430]">-</div>
          <div id="handover-delivery-info" class="text-slate-600 text-[11px]">-</div>
          <div id="handover-payment-info" class="text-slate-700">-</div>
        </div>
        <div id="handover-method-wrapper">
          <label class="block font-bold uppercase text-[11px] mb-1">PELUNASAN SAAT PENGAMBILAN / PENGANTARAN</label>
          <select id="handover-pay-method" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 focus:border-[#1e2430] focus:ring-0 text-xs font-bold">
            <?php foreach (['Tunai', 'QRIS', 'Transfer'] as $pm): ?><option value="<?= $pm ?>"><?= $pm ?></option><?php endforeach; ?>
          </select>
        </div>
        <div class="pt-3 border-t border-slate-300 flex items-center justify-end gap-2">
          <button type="button" data-close-modal="modal-handover" class="px-4 py-2 bg-white border border-slate-300 hover:border-[#1e2430] font-bold">[BATAL]</button>
          <button type="submit" class="px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white font-bold border border-emerald-700">[SAHKAN SERAH TERIMA]</button>
        </div>
      </form>
    </div>
  </div>

  <!-- MODAL 5: INFO AKUN -->
  <div id="modal-account" class="hidden fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white border-2 border-[#1e2430] w-full max-w-md my-auto">
      <div class="px-5 py-3.5 bg-[#1e2430] text-white flex items-center justify-between">
        <h3 class="text-xs font-extrabold tracking-wider uppercase">[VERIFIKASI AKUN &amp; HAK AKSES]</h3>
        <button type="button" data-close-modal="modal-account" class="text-xs font-bold hover:text-slate-300">[X]</button>
      </div>
      <div class="p-4 sm:p-5 space-y-4 text-xs">
        <div class="p-3 bg-slate-100 border border-slate-300 space-y-1.5">
          <div><span class="text-slate-500">KODE STAF:</span> <strong><?= htmlspecialchars($u['code']) ?></strong></div>
          <div><span class="text-slate-500">NAMA LENGKAP:</span> <strong><?= htmlspecialchars($u['name']) ?></strong></div>
          <div><span class="text-slate-500">EMAIL LOGIN:</span> <strong><?= htmlspecialchars($u['email']) ?></strong></div>
          <div><span class="text-slate-500">ROLE / JABATAN:</span> <strong class="px-1.5 py-0.5 bg-[#1e2430] text-white"><?= $roleLabel ?></strong></div>
          <div><span class="text-slate-500">IZIN HARGA &amp; LAYANAN:</span> <strong><?= $isOwner ? 'DIIZINKAN (FULL AKSES)' : 'TERBATAS (HANYA BACA)' ?></strong></div>
          <div><span class="text-slate-500">WAKTU VERIFIKASI:</span> <strong><?= htmlspecialchars($u['login_time']) ?></strong></div>
        </div>
        <div class="flex items-center justify-between pt-2">
          <button type="button" onclick="logoutUser()" class="px-4 py-2 bg-red-700 hover:bg-red-800 text-white font-bold border border-red-700">[ LOGOUT / KELUAR ]</button>
          <button type="button" data-close-modal="modal-account" class="px-4 py-2 bg-[#1e2430] text-white font-bold">[TUTUP]</button>
        </div>
      </div>
    </div>
  </div>

  <?php if ($isOwner): ?>
    <!-- MODAL 6 (KHUSUS OWNER): KELOLA LAYANAN, HARGA & ONGKIR -->
    <div id="modal-services" class="hidden fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4 overflow-y-auto">
      <div class="bg-white border-2 border-[#1e2430] w-full max-w-3xl my-auto">
        <div class="px-5 py-3.5 bg-[#1e2430] text-white flex items-center justify-between">
          <h3 class="text-xs font-extrabold tracking-wider uppercase">[KHUSUS OWNER // KELOLA JENIS CUCIAN, HARGA &amp; ONGKIR]</h3>
          <button type="button" data-close-modal="modal-services" class="text-xs font-bold hover:text-slate-300">[X]</button>
        </div>
        <div class="p-4 sm:p-5 space-y-5 text-xs">
          <form id="form-delivery-fee" class="bg-slate-100 border-2 border-[#1e2430] p-3.5 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
              <div class="font-extrabold text-[#1e2430] uppercase">// TARIF ONGKIR ANTAR-JEMPUT (FLAT PER TRANSAKSI)</div>
              <div class="text-[11px] text-slate-600 mt-0.5">Otomatis ditambahkan saat pelanggan memilih metode Antar-Jemput.</div>
            </div>
            <div class="flex items-center gap-2 shrink-0">
              <input id="owner-delivery-fee-input" type="number" min="0" step="500" value="5000" required class="w-28 sm:w-32 px-3 py-2 bg-white border-2 border-[#1e2430] focus:ring-0 text-xs font-extrabold" />
              <button type="submit" class="px-3.5 sm:px-4 py-2 bg-[#1e2430] hover:bg-slate-800 text-white font-bold whitespace-nowrap">[SIMPAN ONGKIR]</button>
            </div>
          </form>

          <form id="form-add-service" class="bg-slate-50 border border-slate-300 p-4 space-y-3">
            <div class="font-bold text-[#1e2430] uppercase">// + TAMBAH JENIS CUCIAN BARU</div>
            <div class="grid grid-cols-1 sm:grid-cols-12 gap-3">
              <div class="sm:col-span-2">
                <label class="block font-bold text-[10px] mb-1">KODE *</label>
                <input name="code" required placeholder="DRY-07" class="w-full px-2.5 py-1.5 bg-white border border-slate-300 focus:border-[#1e2430] focus:ring-0 text-xs uppercase" type="text" />
              </div>
              <div class="sm:col-span-5">
                <label class="block font-bold text-[10px] mb-1">NAMA JENIS CUCIAN *</label>
                <input name="name" required placeholder="Contoh: Dry Clean Jas / Kebaya" class="w-full px-2.5 py-1.5 bg-white border border-slate-300 focus:border-[#1e2430] focus:ring-0 text-xs" type="text" />
              </div>
              <div class="sm:col-span-2">
                <label class="block font-bold text-[10px] mb-1">SATUAN *</label>
                <select name="unit" class="w-full px-2.5 py-1.5 bg-white border border-slate-300 focus:border-[#1e2430] focus:ring-0 text-xs">
                  <?php foreach (['Kg', 'Pcs', 'Meter', 'Pasang'] as $unit): ?><option value="<?= $unit ?>"><?= $unit ?></option><?php endforeach; ?>
                </select>
              </div>
              <div class="sm:col-span-3">
                <label class="block font-bold text-[10px] mb-1">HARGA (RP) *</label>
                <input name="price_per_unit" required min="500" step="500" placeholder="15000" class="w-full px-2.5 py-1.5 bg-white border border-slate-300 focus:border-[#1e2430] focus:ring-0 text-xs font-bold" type="number" />
              </div>
            </div>
            <div class="flex justify-end">
              <button type="submit" class="px-4 py-2 bg-[#1e2430] hover:bg-slate-800 text-white font-bold">[+ TAMBAH LAYANAN]</button>
            </div>
          </form>

          <div class="border-2 border-[#1e2430] w-full overflow-x-auto">
            <table class="w-full min-w-[540px] text-left text-xs border-collapse">
              <thead class="bg-slate-100 border-b-2 border-[#1e2430] text-[11px] font-bold uppercase">
                <tr>
                  <th class="p-2.5 border-r border-slate-300 whitespace-nowrap">KODE</th>
                  <th class="p-2.5 border-r border-slate-300 whitespace-nowrap">NAMA JENIS CUCIAN</th>
                  <th class="p-2.5 border-r border-slate-300 whitespace-nowrap">SATUAN</th>
                  <th class="p-2.5 border-r border-slate-300 whitespace-nowrap">HARGA (RP)</th>
                  <th class="p-2.5 text-center whitespace-nowrap">AKSI OWNER</th>
                </tr>
              </thead>
              <tbody id="services-manage-tbody" class="divide-y border-slate-300"></tbody>
            </table>
          </div>

          <div class="flex justify-end pt-2 border-t border-slate-300">
            <button type="button" data-close-modal="modal-services" class="px-4 py-2 bg-white border border-slate-300 hover:border-[#1e2430] font-bold">[TUTUP]</button>
          </div>
        </div>
      </div>
    </div>
  <?php endif; ?>

  <script src="js/app.js"></script>
</body>

</html>