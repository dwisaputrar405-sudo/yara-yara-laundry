<?php
$activeNav = $activeNav ?? 'beranda';

$navItems = [
    'beranda' => [
        'label' => '[BERANDA]',
        'href'  => 'index.php',
        'icon'  => '<rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect>'
    ],
    'pesan' => [
        'label' => '[PESAN]',
        'href'  => 'pesan.php',
        'icon'  => '<path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" stroke-linecap="round" stroke-linejoin="round"></path>'
    ],
    'lacak' => [
        'label' => '[LACAK]',
        'href'  => 'lacak.php',
        'icon'  => '<circle cx="12" cy="12" r="9"></circle><circle cx="12" cy="12" r="3"></circle><path d="M12 3v3m0 12v3M3 12h3m12 0h3" stroke-linecap="round" stroke-linejoin="round"></path>'
    ],
    'riwayat' => [
        'label' => '[RIWAYAT]',
        'href'  => 'riwayat.php',
        'icon'  => '<path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" stroke-linecap="round" stroke-linejoin="round"></path>'
    ]
];
?>
<!-- BEGIN: BottomNavigation -->
<nav aria-label="Navigasi Utama" class="fixed bottom-0 left-0 right-0 max-w-md mx-auto bg-white border-t border-slate-300 z-30" data-purpose="bottom-navigation">
    <div class="grid grid-cols-4 text-center">
        <?php foreach ($navItems as $key => $item):
            $isActive = ($activeNav === $key);
            $linkClass = $isActive
                ? 'py-2.5 flex flex-col items-center justify-center gap-1 text-[#181c28] font-bold border-t-2 border-[#181c28] bg-slate-50 -mt-[1px]'
                : 'py-2.5 flex flex-col items-center justify-center gap-1 text-slate-400 hover:text-[#181c28] font-medium transition-colors';
        ?>
        <a href="<?= $item['href'] ?>" <?= $isActive ? 'aria-current="page"' : '' ?> class="<?= $linkClass ?>">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <?= $item['icon'] ?>
            </svg>
            <span class="text-[10px] tracking-tight whitespace-nowrap"><?= $item['label'] ?></span>
        </a>
        <?php endforeach; ?>
    </div>
</nav>
<!-- END: BottomNavigation -->

<script>
    (function () {
        const notifBtn = document.getElementById('btn-top-notif');
        const notifPop = document.getElementById('top-notif-popover');
        const closeNotif = document.getElementById('btn-close-top-notif');

        if (notifBtn && notifPop) {
            notifBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                notifPop.classList.toggle('hidden');
            });
            closeNotif?.addEventListener('click', () => notifPop.classList.add('hidden'));
            document.addEventListener('click', (e) => {
                if (!notifPop.contains(e.target) && !notifBtn.contains(e.target)) {
                    notifPop.classList.add('hidden');
                }
            });
        }
    })();
</script>