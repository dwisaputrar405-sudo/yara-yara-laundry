<?php
$activeNav = $activeNav ?? '';
?>
<!-- BEGIN: TopHeader -->
<header class="sticky top-0 z-40 bg-white border-b border-slate-300 px-4 py-3 flex items-center justify-between" data-purpose="top-navigation">
    <!-- Brand Logo Box -->
    <a href="index.php" class="border border-[#181c28] px-2.5 py-1 text-xs font-bold tracking-wider uppercase bg-slate-50 text-[#181c28] hover:bg-slate-100 transition-colors">
        [YARA YARA]
    </a>

    <!-- Right Action Icons -->
    <div class="flex items-center gap-2.5">
        <a href="profil.php" aria-label="Profil Pengguna" class="w-8 h-8 rounded-full <?= $activeNav === 'profil' ? 'bg-white text-[#181c28] border-2 border-[#181c28]' : 'bg-[#181c28] text-white' ?> flex items-center justify-center transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
        </a>
    </div>
</header>
<!-- END: TopHeader -->