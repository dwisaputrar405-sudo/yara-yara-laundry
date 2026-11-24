<?php
require __DIR__ . '/includes/guard.php';
require_once __DIR__ . '/../config/database.php';

$activeNav = 'pesan';
$cust = $_SESSION['customer'];

$services = $pdo->query("SELECT * FROM services ORDER BY id ASC")->fetchAll();
$feeStmt  = $pdo->query("SELECT setting_value FROM settings WHERE setting_key = 'delivery_fee' LIMIT 1");
$deliveryFee = (int)($feeStmt->fetchColumn() ?: 5000);
$firstSrv = $services[0] ?? ['id' => 1, 'code' => 'CK-01', 'name' => 'Cuci Komplit', 'unit' => 'Kg', 'price_per_unit' => 8000];

function getEstLabel($code) {
    if (str_starts_with($code, 'EXP')) return '6 Jam';
    if (str_starts_with($code, 'BC')) return '3 Hari Kerja';
    if (str_starts_with($code, 'ST') || str_starts_with($code, 'CKL')) return '1 Hari Kerja';
    return '2 Hari Kerja';
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
    <title>04. Buat Pesanan Laundry - YARA YARA</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet">
    <style data-purpose="typography-and-theme">
        body {
            font-family: 'JetBrains Mono', ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            background-color: #f4f5f7;
            color: #181c28;
            -webkit-tap-highlight-color: transparent;
        }
    </style>
    <style data-purpose="brutalist-components">
        .brutal-card-selected {
            border: 2px solid #181c28;
            background-color: #FFFFFF;
        }

        .brutal-card-unselected {
            border: 1px solid #94A3B8;
            background-color: #FFFFFF;
        }

        .brutal-input {
            border: 1px solid #64748B;
            background-color: #FFFFFF;
            border-radius: 0;
        }

        .brutal-input:focus {
            outline: none;
            border: 2px solid #181c28;
            box-shadow: none;
        }
    </style>
</head>

<body class="min-h-screen pb-24">
    <div class="max-w-md mx-auto min-h-screen flex flex-col bg-[#f4f5f7] border-x border-slate-200">
        <?php require __DIR__ . '/includes/header.php'; ?>

        <main class="flex-1 px-4 pt-4 pb-6 w-full space-y-6">
            <section class="border-b border-slate-300 pb-3" data-purpose="page-header">
                <a class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-[#181c28] mb-2" href="index.php">
                    <span>←</span>
                    <span>[KEMBALI KE DASHBOARD]</span>
                </a>
                <h1 class="text-base font-extrabold tracking-tight text-[#181c28]">
                    [BUAT PESANAN LAUNDRY]
                </h1>
            </section>

            <section class="space-y-2.5" data-purpose="service-selection">
                <h2 class="text-xs font-bold tracking-wide text-[#181c28]">
                    [1. PILIH JENIS LAYANAN]
                </h2>
                <?php foreach ($services as $idx => $srv): ?>
                    <?php
                    $isFirst = $idx === 0;
                    $unitUp = strtoupper($srv['unit']);
                    $estText = getEstLabel($srv['code']);
                    ?>
                    <div class="service-option <?= $isFirst ? 'brutal-card-selected' : 'brutal-card-unselected' ?> p-3 cursor-pointer transition-all"
                         data-id="<?= (int)$srv['id'] ?>"
                         data-est="<?= htmlspecialchars($estText) ?>"
                         data-price="<?= (int)$srv['price_per_unit'] ?>"
                         data-service="<?= htmlspecialchars(strtoupper($srv['name'])) ?>"
                         data-unit="<?= htmlspecialchars($unitUp) ?>">
                        <div class="flex items-start justify-between gap-2">
                            <div class="flex items-start gap-2.5">
                                <span class="service-checkbox w-4 h-4 mt-0.5 flex items-center justify-center <?= $isFirst ? 'bg-[#181c28] text-white' : 'border border-[#181c28] bg-white text-transparent' ?> text-[10px] font-bold shrink-0">X</span>
                                <div>
                                    <p class="text-xs font-bold leading-snug"><?= htmlspecialchars(strtoupper($srv['name'])) ?></p>
                                    <p class="text-[11px] text-slate-600 mt-1">RP <?= number_format((int)$srv['price_per_unit'], 0, ',', '.') ?> / <?= htmlspecialchars($unitUp) ?></p>
                                </div>
                            </div>
                            <div class="text-right shrink-0 flex flex-col items-end justify-between gap-2">
                                <span class="selected-badge <?= $isFirst ? '' : 'hidden' ?> bg-[#181c28] text-white text-[10px] font-bold px-1.5 py-0.5">TERPILIH</span>
                                <span class="text-[10px] font-semibold <?= $isFirst ? 'text-slate-700' : 'text-slate-500' ?>">EST: <?= htmlspecialchars(strtoupper($estText)) ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </section>

            <section class="space-y-2.5" data-purpose="weight-adjuster">
                <div class="flex items-center justify-between">
                    <h2 class="text-xs font-bold tracking-wide text-[#181c28]">
                        [2. ESTIMASI BERAT / JUMLAH]
                    </h2>
                    <span class="text-[10px] text-slate-500 font-semibold" id="unit-label">SATUAN: <?= htmlspecialchars(strtoupper($firstSrv['unit'])) ?></span>
                </div>
                <div class="border border-[#181c28] bg-white p-4 flex items-center justify-center gap-4">
                    <button class="w-11 h-11 border border-[#181c28] bg-white hover:bg-slate-100 active:bg-slate-200 text-sm font-bold flex items-center justify-center" id="btn-minus" type="button">
                        [-]
                    </button>
                    <div class="w-28 h-11 border border-[#181c28] bg-[#eef2f9] flex items-center justify-center">
                        <span class="text-base font-extrabold tracking-tight" id="weight-display">4.0 <?= htmlspecialchars(strtoupper($firstSrv['unit'])) ?></span>
                    </div>
                    <button class="w-11 h-11 border border-[#181c28] bg-white hover:bg-slate-100 active:bg-slate-200 text-sm font-bold flex items-center justify-center" id="btn-plus" type="button">
                        [+]
                    </button>
                </div>
            </section>

            <section class="space-y-2.5" data-purpose="handover-method">
                <h2 class="text-xs font-bold tracking-wide text-[#181c28]">
                    [3. METODE SERAH TERIMA]
                </h2>
                <div class="method-option brutal-card-selected p-3 cursor-pointer flex items-center justify-between gap-2" data-fee="0" data-method="Self-Pickup">
                    <div class="flex items-center gap-2.5">
                        <span class="method-checkbox w-4 h-4 flex items-center justify-center bg-[#181c28] text-white text-[10px] font-bold shrink-0">X</span>
                        <span class="text-xs font-bold">AMBIL SENDIRI DI OUTLET</span>
                    </div>
                    <span class="text-xs font-bold shrink-0">RP 0</span>
                </div>
                <div class="method-option brutal-card-unselected p-3 cursor-pointer flex items-center justify-between gap-2" data-fee="<?= $deliveryFee ?>" data-method="Kurir Outlet">
                    <div class="flex items-center gap-2.5">
                        <span class="method-checkbox w-4 h-4 flex items-center justify-center border border-[#181c28] bg-white text-transparent text-[10px] font-bold shrink-0">X</span>
                        <span class="text-xs font-bold">ANTAR-JEMPUT (KURIR)</span>
                    </div>
                    <span class="text-xs font-bold shrink-0">+ RP <?= number_format($deliveryFee, 0, ',', '.') ?></span>
                </div>
                <div class="hidden pt-1" id="address-container">
                    <input class="brutal-input w-full px-3 py-2.5 text-xs placeholder:text-slate-400" id="delivery-address" placeholder="[Alamat penjemputan / pengantaran]" type="text" value="<?= htmlspecialchars($cust['address'] ?? '') ?>">
                </div>
            </section>

            <section class="space-y-2" data-purpose="short-note">
                <div class="flex items-center justify-between">
                    <h2 class="text-xs font-bold tracking-wide text-[#181c28]">
                        [4. CATATAN SINGKAT]
                    </h2>
                    <span class="text-[10px] text-slate-500">OPSIONAL</span>
                </div>
                <textarea class="brutal-input w-full p-3 text-xs placeholder:text-slate-400 resize-none" id="order-note" placeholder="[Misal: pisahkan pakaian putih, ada noda di kerah]" rows="2"></textarea>
            </section>

            <section class="space-y-2.5" data-purpose="cost-summary">
                <h2 class="text-xs font-bold tracking-wide text-[#181c28]">
                    [5. RINGKASAN ESTIMASI BIAYA]
                </h2>
                <div class="border border-[#181c28] bg-[#eef2f9] p-4 space-y-2.5">
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-slate-700" id="summary-service-label"><?= htmlspecialchars(strtoupper($firstSrv['name'])) ?> (4.0 <?= htmlspecialchars(strtoupper($firstSrv['unit'])) ?>)</span>
                        <span class="font-bold" id="summary-subtotal">Rp <?= number_format(4 * (int)$firstSrv['price_per_unit'], 0, ',', '.') ?></span>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-slate-700" id="summary-method-label">Biaya Kurir (Self-Pickup)</span>
                        <span class="font-bold" id="summary-fee">Rp 0</span>
                    </div>
                    <div class="border-t border-dashed border-slate-400 pt-2.5 flex items-center justify-between">
                        <span class="text-xs font-extrabold">TOTAL ESTIMASI</span>
                        <span class="text-base font-extrabold" id="summary-total">Rp <?= number_format(4 * (int)$firstSrv['price_per_unit'], 0, ',', '.') ?></span>
                    </div>
                    <div class="border border-slate-400 bg-white px-2.5 py-1.5 flex items-center gap-2 text-[11px]">
                        <svg class="w-3.5 h-3.5 text-[#181c28] shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                        <span class="font-semibold" id="summary-est">ESTIMASI SELESAI: <?= htmlspecialchars(getEstLabel($firstSrv['code'])) ?></span>
                    </div>
                </div>
            </section>

            <section class="space-y-2.5 pt-1" data-purpose="form-actions">
                <button class="w-full py-3.5 bg-[#181c28] hover:bg-slate-800 active:bg-black text-white text-xs font-bold tracking-wider transition-colors" id="btn-submit" type="button">
                    [LANJUTKAN KE KONFIRMASI PESANAN]
                </button>
                <a href="index.php" class="block text-center w-full py-3 bg-white hover:bg-slate-100 border border-[#181c28] text-[#181c28] text-xs font-bold tracking-wider transition-colors" id="btn-cancel">
                    [BATALKAN]
                </a>
            </section>
        </main>

        <?php require __DIR__ . '/includes/bottom-nav.php'; ?>
    </div>

    <script data-purpose="order-state-and-calculations">
        const state = {
            serviceId: <?= (int)$firstSrv['id'] ?>,
            serviceName: <?= json_encode(strtoupper($firstSrv['name'])) ?>,
            unitPrice: <?= (int)$firstSrv['price_per_unit'] ?>,
            unit: <?= json_encode(strtoupper($firstSrv['unit'])) ?>,
            est: <?= json_encode(getEstLabel($firstSrv['code'])) ?>,
            weight: 4.0,
            methodName: 'Self-Pickup',
            deliveryFee: 0
        };

        const formatRupiah = (num) => 'Rp ' + num.toLocaleString('id-ID');

        const updateSummary = () => {
            const subtotal = Math.round(state.weight * state.unitPrice);
            const total = subtotal + state.deliveryFee;
            const weightText = state.unit === 'KG' ? state.weight.toFixed(1) : Math.round(state.weight);

            document.getElementById('weight-display').textContent = `${weightText} ${state.unit}`;
            document.getElementById('unit-label').textContent = `SATUAN: ${state.unit}`;
            document.getElementById('summary-service-label').textContent = `${state.serviceName} (${weightText} ${state.unit})`;
            document.getElementById('summary-subtotal').textContent = formatRupiah(subtotal);
            document.getElementById('summary-method-label').textContent = `Biaya Kurir (${state.methodName})`;
            document.getElementById('summary-fee').textContent = formatRupiah(state.deliveryFee);
            document.getElementById('summary-total').textContent = formatRupiah(total);
            document.getElementById('summary-est').textContent = `ESTIMASI SELESAI: ${state.est}`;
        };

        document.querySelectorAll('.service-option').forEach((card) => {
            card.addEventListener('click', () => {
                document.querySelectorAll('.service-option').forEach((c) => {
                    c.classList.remove('brutal-card-selected');
                    c.classList.add('brutal-card-unselected');
                    const box = c.querySelector('.service-checkbox');
                    box.classList.remove('bg-[#181c28]', 'text-white');
                    box.classList.add('border', 'border-[#181c28]', 'bg-white', 'text-transparent');
                    c.querySelector('.selected-badge')?.classList.add('hidden');
                });

                card.classList.remove('brutal-card-unselected');
                card.classList.add('brutal-card-selected');
                const activeBox = card.querySelector('.service-checkbox');
                activeBox.classList.remove('border', 'bg-white', 'text-transparent');
                activeBox.classList.add('bg-[#181c28]', 'text-white');
                card.querySelector('.selected-badge')?.classList.remove('hidden');

                state.serviceId = parseInt(card.dataset.id, 10);
                state.serviceName = card.dataset.service;
                state.unitPrice = parseInt(card.dataset.price, 10);
                state.unit = card.dataset.unit;
                state.est = card.dataset.est;
                if (state.unit === 'PCS') state.weight = Math.max(1, Math.round(state.weight));
                updateSummary();
            });
        });

        document.getElementById('btn-minus').addEventListener('click', () => {
            const step = state.unit === 'KG' ? 0.5 : 1;
            if (state.weight - step >= 1.0) {
                state.weight = Math.round((state.weight - step) * 10) / 10;
                updateSummary();
            }
        });

        document.getElementById('btn-plus').addEventListener('click', () => {
            const step = state.unit === 'KG' ? 0.5 : 1;
            state.weight = Math.round((state.weight + step) * 10) / 10;
            updateSummary();
        });

        document.querySelectorAll('.method-option').forEach((card) => {
            card.addEventListener('click', () => {
                document.querySelectorAll('.method-option').forEach((c) => {
                    c.classList.remove('brutal-card-selected');
                    c.classList.add('brutal-card-unselected');
                    const box = c.querySelector('.method-checkbox');
                    box.classList.remove('bg-[#181c28]', 'text-white');
                    box.classList.add('border', 'border-[#181c28]', 'bg-white', 'text-transparent');
                });

                card.classList.remove('brutal-card-unselected');
                card.classList.add('brutal-card-selected');
                const activeBox = card.querySelector('.method-checkbox');
                activeBox.classList.remove('border', 'bg-white', 'text-transparent');
                activeBox.classList.add('bg-[#181c28]', 'text-white');

                state.methodName = card.dataset.method;
                state.deliveryFee = parseInt(card.dataset.fee, 10);
                document.getElementById('address-container').classList.toggle('hidden', state.deliveryFee === 0);
                updateSummary();
            });
        });

        document.getElementById('btn-submit').addEventListener('click', () => {
            const addressInput = document.getElementById('delivery-address').value.trim();
            if (state.deliveryFee > 0 && !addressInput) {
                alert('Mohon isi alamat penjemputan / pengantaran terlebih dahulu.');
                document.getElementById('delivery-address').focus();
                return;
            }
            const weightText = state.unit === 'KG' ? state.weight.toFixed(1) : Math.round(state.weight);
            const total = Math.round(state.weight * state.unitPrice) + state.deliveryFee;

            sessionStorage.setItem('yaraDraftOrder', JSON.stringify({
                serviceId: state.serviceId,
                serviceName: state.serviceName,
                unitPrice: state.unitPrice,
                unit: state.unit,
                weightQty: state.weight,
                weightText: weightText,
                deliveryType: state.deliveryFee > 0 ? 'ANTAR_JEMPUT' : 'DROP_OFF',
                methodLabel: state.deliveryFee > 0 ? 'Antar-Jemput (Kurir)' : 'Ambil Sendiri',
                deliveryFee: state.deliveryFee,
                address: state.deliveryFee > 0 ? addressInput : '',
                note: document.getElementById('order-note').value.trim(),
                total: total
            }));

            window.location.href = 'konfirmasi.php';
        });
    </script>
</body>

</html>