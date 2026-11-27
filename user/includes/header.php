<?php
$activeNav = $activeNav ?? '';
?>
<!-- Top header -->
<header class="sticky top-0 z-40 bg-[#F8FBFF] border-b border-[#C9D9EA] px-4 py-3 flex items-center justify-between" data-purpose="top-navigation">
    <a href="index.php" class="border border-[#0F2854] px-2.5 py-1 text-xs font-bold tracking-wider uppercase bg-[#F5F9FE] text-[#0F2854] hover:bg-[#EAF2FA] transition-colors">
        YARA YARA
    </a>

    <div class="flex items-center gap-2.5">
        <a href="profil.php" aria-label="Profil Pengguna" class="w-8 h-8 rounded-full <?= $activeNav === 'profil' ? 'bg-[#F8FBFF] text-[#0F2854] border-2 border-[#0F2854]' : 'bg-[#0F2854] text-white' ?> flex items-center justify-center transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
        </a>
    </div>
</header>