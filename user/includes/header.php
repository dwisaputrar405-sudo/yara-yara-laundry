<?php
$activeNav = $activeNav ?? '';
$notifOrders = [];
$hasActiveNotif = false;

if (isset($_SESSION['customer']['id'])) {
    require_once __DIR__ . '/../../config/database.php';
    $stmtNotif = $pdo->prepare("
        SELECT o.receipt_no, o.status_step, o.status_note, o.time_label, s.name AS service_name
        FROM orders o
        JOIN services s ON s.id = o.service_id
        WHERE o.customer_id = ?
        ORDER BY o.id DESC
        LIMIT 3
    ");
    $stmtNotif->execute([(int)$_SESSION['customer']['id']]);
    $notifOrders = $stmtNotif->fetchAll();

    foreach ($notifOrders as $no) {
        if ((int)$no['status_step'] < 7) {
            $hasActiveNotif = true;
            break;
        }
    }
}

$notifStageLabels = [
    0 => 'MENUNGGU JEMPUT',
    1 => 'MENUNGGU TIMBANG',
    2 => 'ANTRIAN CUCI',
    3 => 'SEDANG DICUCI',
    4 => 'PENGERINGAN',
    5 => 'SETRIKA & PACKING',
    6 => 'SIAP DIAMBIL/DIANTAR',
    7 => 'SELESAI'
];
?>
<!-- BEGIN: TopHeader -->
<header class="sticky top-0 z-40 bg-white border-b border-slate-300 px-4 py-3 flex items-center justify-between" data-purpose="top-navigation">
    <!-- Brand Logo Box -->
    <a href="index.php" class="border border-[#181c28] px-2.5 py-1 text-xs font-bold tracking-wider uppercase bg-slate-50 text-[#181c28] hover:bg-slate-100 transition-colors">
        [YARA YARA]
    </a>

    <!-- Right Action Icons -->
    <div class="flex items-center gap-2.5">
        <button id="btn-top-notif"
                onclick="document.getElementById('top-notif-popover').classList.toggle('hidden')"
                aria-label="Notifikasi"
                class="relative p-1.5 text-[#181c28] hover:bg-slate-100 active:bg-slate-200 transition-colors cursor-pointer"
                type="button">
            <svg class="w-5 h-5 pointer-events-none" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
            <?php if ($hasActiveNotif): ?>
                <span class="pointer-events-none absolute top-1.5 right-1.5 w-2 h-2 bg-[#181c28]"></span>
            <?php endif; ?>
        </button>

        <a href="profil.php" aria-label="Profil Pengguna" class="w-8 h-8 rounded-full <?= $activeNav === 'profil' ? 'bg-white text-[#181c28] border-2 border-[#181c28]' : 'bg-[#181c28] text-white' ?> flex items-center justify-center transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
        </a>
    </div>

    <!-- Popover Notifikasi Ringkas -->
    <div id="top-notif-popover" class="hidden absolute right-4 top-14 w-72 bg-white border-2 border-[#181c28] p-3 z-50 shadow-md text-xs">
        <div class="flex items-center justify-between border-b border-slate-200 pb-2 mb-2 font-bold">
            <span>[NOTIFIKASI SISTEM]</span>
            <button id="btn-close-top-notif"
                    onclick="document.getElementById('top-notif-popover').classList.add('hidden')"
                    type="button"
                    class="text-slate-500 hover:text-[#181c28] px-1 cursor-pointer">[X]</button>
        </div>
        <div class="space-y-2 text-[11px]">
            <?php if (empty($notifOrders)): ?>
                <div class="bg-slate-50 p-2.5 border border-slate-200 text-slate-500 text-center">
                    Belum ada notifikasi pesanan.
                </div>
            <?php else: ?>
                <?php foreach ($notifOrders as $no): ?>
                    <?php
                    $nStep  = (int)$no['status_step'];
                    $isDone = $nStep >= 7;
                    $targetHref = $isDone
                        ? 'riwayat.php#' . urlencode(ltrim($no['receipt_no'], '#'))
                        : 'lacak.php?code=' . urlencode($no['receipt_no']);
                    $stageText = $notifStageLabels[$nStep] ?? 'DIPROSES';
                    ?>
                    <a href="<?= $targetHref ?>" class="block <?= $isDone ? 'bg-slate-50 border-slate-200' : 'bg-[#eef2f9] border-slate-300' ?> p-2 border hover:border-[#181c28]">
                        <div class="font-bold text-[#181c28]"><?= htmlspecialchars($no['receipt_no']) ?> • <?= $stageText ?></div>
                        <div class="text-slate-600 mt-0.5 truncate"><?= htmlspecialchars($no['status_note']) ?></div>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</header>
<!-- END: TopHeader -->