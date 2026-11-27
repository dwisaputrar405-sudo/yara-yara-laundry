const API = "api/index.php";
const ROLE = document.body.dataset.userRole || "kasir";
const $ = (id) => document.getElementById(id);
const rp = (n) => "Rp " + Number(n || 0).toLocaleString("id-ID");
const pad = (n) => String(n).padStart(2, "0");
const toggleModal = (id, show) => $(id)?.classList.toggle("hidden", !show);

const state = {
  activeNav: "RINGKASAN",
  filter: "ALL",
  search: "",
  page: 1,
  limit: 5,
  services: [],
  deliveryFee: 5000,
};

// Deteksi layanan khusus (Setrika Saja / Cuci Kering Lipat)
function getServiceProfile(serviceName) {
  const s = String(serviceName || "").toUpperCase();
  const isIronOnly = s.includes("SETRIKA") && !s.includes("CUCI");
  const isFoldOnly = s.includes("LIPAT") && !s.includes("SETRIKA");
  const skippedSteps = isIronOnly ? [2, 3, 4] : isFoldOnly ? [5] : [];
  return {
    isIronOnly,
    isFoldOnly,
    skippedSteps,
    postWeighStep: isIronOnly ? 5 : 3,
    defaultNote: isIronOnly ? "Meja Setrika-1 (ETA 17:00)" : "Drum-A1 (ETA 17:00)",
  };
}

function syncStepSelectOptions(selectEl, serviceName, currentStep = null) {
  if (!selectEl) return;
  const { skippedSteps } = getServiceProfile(serviceName);
  Array.from(selectEl.options).forEach((opt) => {
    const val = Number(opt.value);
    const shouldSkip = skippedSteps.includes(val) && val !== Number(currentStep);
    opt.hidden = shouldSkip;
    opt.disabled = shouldSkip;
  });
}

// Pembungkus API terpusat (otomatis handle JSON, error alert, & redirect jika sesi habis)
async function api(query, method = "GET", body = null) {
  const opts = { method, headers: {} };
  if (body) {
    opts.headers["Content-Type"] = "application/json";
    opts.body = JSON.stringify(body);
  }
  const res = await fetch(`${API}?${query}`, opts);
  if (res.status === 401) {
    window.location.href = "login-admin.php";
    throw new Error("Sesi berakhir");
  }
  const data = await res.json();
  if (!res.ok) {
    alert(data.error || "Terjadi kesalahan sistem.");
    throw new Error(data.error);
  }
  return data;
}

window.logoutUser = async () => {
  await api("route=auth&action=logout", "POST");
  window.location.href = "login-admin.php";
};

document
  .querySelectorAll("[data-close-modal]")
  .forEach((b) =>
    b.addEventListener("click", () => toggleModal(b.dataset.closeModal, false)),
  );

// Cetak Resi Digital Resmi 1 Halaman (Sama dengan resi pelanggan)
window.printOrderReceipt = async (id) => {
  const o = await api(`route=orders&id=${id}`);
  const printArea = $("admin-print-area");
  if (!printArea) return;

  const cleanId = String(o.receipt_no || "").replace(/[^0-9]/g, "") || String(o.id);
  const rawDate = (o.updated_at || o.created_at || "").slice(0, 10).replace(/-/g, "");
  const dateCode = rawDate.length === 8 ? rawDate : new Date().toISOString().slice(0, 10).replace(/-/g, "");
  const trxNo = `REC-${dateCode}-${cleanId}`;

  const d = o.updated_at ? new Date(o.updated_at.replace(" ", "T")) : new Date();
  const timeStr = isNaN(d.getTime())
    ? `${o.time_label || "-"} WIB`
    : `${pad(d.getDate())}/${pad(d.getMonth() + 1)}/${d.getFullYear()} ${pad(d.getHours())}:${pad(d.getMinutes())} WIB`;

  const unit = String(o.service_unit || "Kg");
  const qtyText = unit.toUpperCase() === "KG"
    ? `${Number(o.weight).toFixed(1)} Kg`
    : `${Math.round(Number(o.weight))} ${unit}`;

  const payMethod = o.payment_status === "LUNAS"
    ? String(o.payment_method || "TUNAI OUTLET").toUpperCase()
    : "BELUM LUNAS";

  const totalFmt = Number(o.total_price || 0).toLocaleString("id-ID");

  printArea.innerHTML = `
    <div class="text-center border-b border-dashed border-[#B8CDE2] pb-2">
      <p class="font-bold tracking-wider">BUKTI RESI DIGITAL RESMI</p>
      <p class="text-[10px] text-slate-500">YARA YARA LAUNDRY SYSTEM</p>
    </div>
    <div class="space-y-1 text-[10px]">
      <div class="flex justify-between">
        <span class="text-slate-500">NO. TRANSAKSI</span>
        <span class="font-bold">${trxNo}</span>
      </div>
      <div class="flex justify-between">
        <span class="text-slate-500">PELANGGAN</span>
        <span class="font-bold">${o.customer_name}</span>
      </div>
      <div class="flex justify-between">
        <span class="text-slate-500">WAKTU SELESAI</span>
        <span>${timeStr}</span>
      </div>
      <div class="flex justify-between">
        <span class="text-slate-500">ITEM</span>
        <span>${o.service_name} (${qtyText})</span>
      </div>
      <div class="flex justify-between">
        <span class="text-slate-500">METODE BAYAR</span>
        <span class="font-bold">${payMethod}</span>
      </div>
    </div>
    <div class="flex justify-between items-center border-t border-dashed border-[#B8CDE2] pt-2 font-bold text-xs">
      <span>TOTAL PEMBAYARAN</span>
      <span>RP ${totalFmt}</span>
    </div>
  `;

  window.print();
};

window.addEventListener("afterprint", () => {
  const printArea = $("admin-print-area");
  if (printArea) printArea.innerHTML = "";
});

// Jam Real-Time WIB
function startClock() {
  const days = ["MINGGU", "SENIN", "SELASA", "RABU", "KAMIS", "JUMAT", "SABTU"];
  const months = [
    "JAN",
    "FEB",
    "MAR",
    "APR",
    "MEI",
    "JUN",
    "JUL",
    "AGU",
    "SEP",
    "OKT",
    "NOV",
    "DES",
  ];
  const tick = () => {
    const d = new Date(
      new Date().toLocaleString("en-US", { timeZone: "Asia/Jakarta" }),
    );
    $("live-clock").textContent =
      `${days[d.getDay()]}, ${pad(d.getDate())} ${months[d.getMonth()]} ${d.getFullYear()} - ${pad(d.getHours())}:${pad(d.getMinutes())}:${pad(d.getSeconds())} WIB`;
    if ($("summary-month-label")) {
      $("summary-month-label").textContent = `BULAN: ${months[d.getMonth()]} ${d.getFullYear()}`;
    }
  };
  tick();
  setInterval(tick, 1000);
}

// Muat Layanan & Tarif Ongkir
async function loadServices() {
  const data = await api("route=services");
  state.services = data.services || [];
  state.deliveryFee = Number(data.delivery_fee || 5000);

  const opts = state.services
    .map(
      (s) =>
        `<option value="${s.id}" data-name="${s.name}" data-price="${s.price_per_unit}" data-unit="${s.unit}">${s.name} (${rp(s.price_per_unit)}/${s.unit})</option>`,
    )
    .join("");
  $("create-service-select").innerHTML = opts;
  $("edit-service-select").innerHTML = opts;
  calcCreateTotal(true);

  if (ROLE === "owner" && $("services-manage-tbody")) {
    $("owner-delivery-fee-input").value = state.deliveryFee;
    $("services-manage-tbody").innerHTML = state.services
      .map(
        (s) => `
        <tr>
          <td class="p-2 border-r border-[#C9D9EA]"><input id="sc-${s.id}" value="${s.code}" class="w-20 px-2 py-1 bg-[#F5F9FE] border border-[#C9D9EA] text-xs font-bold uppercase" /></td>
          <td class="p-2 border-r border-[#C9D9EA]"><input id="sn-${s.id}" value="${s.name}" class="w-full px-2 py-1 bg-[#F5F9FE] border border-[#C9D9EA] text-xs font-semibold" /></td>
          <td class="p-2 border-r border-[#C9D9EA]">
            <select id="su-${s.id}" class="px-2 py-1 bg-[#F5F9FE] border border-[#C9D9EA] text-xs">
              ${["Kg", "Pcs", "Meter", "Pasang"].map((u) => `<option value="${u}" ${s.unit === u ? "selected" : ""}>${u}</option>`).join("")}
            </select>
          </td>
          <td class="p-2 border-r border-[#C9D9EA]"><input id="sp-${s.id}" value="${s.price_per_unit}" min="500" step="500" type="number" class="w-28 px-2 py-1 bg-[#F5F9FE] border-2 border-[#0F2854] text-xs font-extrabold" /></td>
          <td class="p-2 text-center whitespace-nowrap space-x-1">
            <button type="button" onclick="saveService(${s.id})" class="px-2.5 py-1 bg-[#0F2854] text-white text-[11px] font-bold">[SIMPAN]</button>
            <button type="button" onclick="delService(${s.id}, '${s.name}')" class="px-2 py-1 bg-[#F8FBFF] text-red-700 border border-red-700 text-[11px] font-bold">[HAPUS]</button>
          </td>
        </tr>
      `,
      )
      .join("");
  }
}

function calcCreateTotal(serviceChanged = false) {
  const sel = $("create-service-select");
  const opt = sel.options[sel.selectedIndex];
  const rate = Number(opt?.dataset.price || 0);
  const unit = String(opt?.dataset.unit || "Kg");
  const srvName = String(opt?.dataset.name || opt?.textContent || "");
  const isKg = unit.toUpperCase() === "KG";

  const wInput = $("create-weight-input");
  const qtyLabel = $("create-qty-label");
  if (qtyLabel) {
    qtyLabel.textContent = isKg ? "BERAT (KG) *" : `JUMLAH (${unit.toUpperCase()}) *`;
  }

  wInput.step = isKg ? "0.1" : "1";
  wInput.min = isKg ? "0.1" : "1";
  if (serviceChanged) {
    wInput.value = isKg ? "3.0" : "1";
  }

  const w = isKg
    ? parseFloat(wInput.value) || 0
    : Math.max(1, Math.round(parseFloat(wInput.value) || 0));
  const fee = Number($("create-delivery-fee").value) || 0;
  const minTotal = Math.round(w * rate) + fee;

  const totInput = $("create-total-input");
  totInput.readOnly = isKg;
  totInput.min = minTotal;
  totInput.value = minTotal;
  totInput.className = isKg
    ? "w-full px-3 py-2 bg-[#EAF2FA] border border-[#0F2854] focus:ring-0 text-xs font-extrabold"
    : "w-full px-3 py-2 bg-[#F8FBFF] border-2 border-[#0F2854] focus:ring-0 text-xs font-extrabold";

  const hint = $("create-price-hint");
  if (hint) {
    hint.classList.toggle("hidden", isKg);
    hint.textContent = `Minimal ${rp(minTotal)} (bisa dinaikkan sesuai barang).`;
  }

  // Sesuaikan tahap awal pada Modal Create jika Setrika Saja
  const { isIronOnly } = getServiceProfile(srvName);
  const stepSel = $("create-status-step");
  const directOpt = stepSel?.querySelector('option[value="3"], option[value="5"]');
  if (directOpt) {
    directOpt.value = isIronOnly ? "5" : "3";
    directOpt.textContent = isIronOnly ? "[5/7] LANGSUNG SETRIKA" : "[3/7] LANGSUNG CUCI";
  }
}

function toggleCreateDelivery() {
  const isDelivery = $("create-delivery-type").value === "ANTAR_JEMPUT";
  $("create-address-wrapper").classList.toggle("hidden", !isDelivery);
  $("create-pickup-address").required = isDelivery;
  if (!isDelivery) $("create-pickup-address").value = "";
  $("create-delivery-fee").value = isDelivery ? state.deliveryFee : 0;
  $("create-status-step").value = isDelivery ? "0" : "1";
  calcCreateTotal(false);
}

$("create-delivery-type").addEventListener("change", toggleCreateDelivery);
$("create-service-select").addEventListener("change", () => calcCreateTotal(true));
$("create-weight-input").addEventListener("input", () => calcCreateTotal(false));

// Owner Actions (Layanan & Ongkir)
window.openServicesModal = async () => {
  await loadServices();
  toggleModal("modal-services", true);
};

$("form-delivery-fee")?.addEventListener("submit", async (e) => {
  e.preventDefault();
  await api("route=services&action=delivery_fee", "PUT", {
    delivery_fee: Number($("owner-delivery-fee-input").value),
  });
  await loadServices();
  alert("Tarif ongkir diperbarui.");
});

$("form-add-service")?.addEventListener("submit", async (e) => {
  e.preventDefault();
  const fd = new FormData(e.target);
  await api("route=services", "POST", Object.fromEntries(fd));
  e.target.reset();
  await loadServices();
});

window.saveService = async (id) => {
  await api(`route=services&id=${id}`, "PUT", {
    code: $(`sc-${id}`).value,
    name: $(`sn-${id}`).value,
    unit: $(`su-${id}`).value,
    price_per_unit: Number($(`sp-${id}`).value),
  });
  await loadServices();
  alert("Tarif layanan berhasil disimpan.");
};

window.delService = async (id, name) => {
  if (!confirm(`Hapus layanan "${name}"?`)) return;
  await api(`route=services&id=${id}`, "DELETE");
  await loadServices();
};

function syncNavHighlight() {
  document.querySelectorAll(".nav-item").forEach((el) => {
    const nav = el.dataset.nav;
    const isMobileChip = el.classList.contains("shrink-0");
    const isActive = nav === state.activeNav;

    if (isMobileChip) {
      el.className = isActive
        ? "nav-item shrink-0 flex items-center gap-1.5 px-2.5 py-1.5 bg-[#0F2854] text-white text-[11px] font-bold tracking-wide border border-[#0F2854]"
        : "nav-item shrink-0 flex items-center gap-1.5 px-2.5 py-1.5 bg-[#F8FBFF] text-[#0F2854] hover:bg-[#EAF2FA] text-[11px] font-semibold tracking-wide border border-[#C9D9EA] transition-colors";
    } else {
      el.className = isActive
        ? "nav-item flex items-center gap-2.5 lg:gap-3 px-3 py-2.5 bg-[#0F2854] text-white text-xs font-bold tracking-wide border border-[#0F2854]"
        : "nav-item flex items-center gap-2.5 lg:gap-3 px-3 py-2.5 bg-[#F8FBFF] text-[#0F2854] hover:bg-[#EAF2FA] text-xs font-semibold tracking-wide border border-transparent hover:border-[#C9D9EA] transition-colors";
    }
  });
}

function formatAvgDuration(minutes) {
  const m = Math.round(Number(minutes || 0));
  if (m <= 0) return "-";
  if (m < 60) return `${m} MNT`;
  const hours = (m / 60).toFixed(1).replace(/\.0$/, "");
  return `${hours} JAM`;
}

// Rekap, Pendapatan & Notifikasi
async function loadSummary() {
  const { stats, overdueOrders } = await api("route=orders&action=summary");
  $("summary-total-unit").textContent = `TOTAL: ${stats.active_total} UNIT`;
  $("stat-active").textContent = pad(stats.active_total);
  $("stat-weighing").textContent = pad(stats.need_weighing);
  $("stat-process").textContent = pad(stats.in_process);
  $("stat-ready").textContent = pad(stats.ready_pickup);

  // Ringkasan Pendapatan & Kinerja Outlet
  if ($("stat-rev-today")) {
    $("stat-rev-today").textContent = rp(stats.rev_today);
    $("stat-rev-today-split").textContent =
      `Tunai ${rp(stats.rev_today_cash)} | Non-Tunai ${rp(stats.rev_today_noncash)}`;
    $("stat-rev-month").textContent = rp(stats.rev_month);
    $("stat-unpaid-total").textContent = `Belum Lunas: ${rp(stats.unpaid_total)}`;
    $("stat-vol-kg").textContent = `${Number(stats.vol_kg_month || 0).toFixed(1)} KG`;
    $("stat-vol-pcs").textContent = `+ ${Math.round(Number(stats.vol_pcs_month || 0))} Item Satuan`;
    $("stat-avg-duration").textContent =
      Number(stats.done_total || 0) > 0 ? formatAvgDuration(stats.avg_done_minutes) : "-";
    $("stat-done-count").textContent = `Dari ${Number(stats.done_total || 0)} Tiket Selesai`;
  }

  const counts = {
    ALL: `SEMUA (${stats.active_total})`,
    BARU: `BARU (${stats.need_weighing})`,
    ANTAR_JEMPUT: `ANTAR-JEMPUT (${stats.antar_jemput_total})`,
    DICUCI: `DICUCI (${stats.washing_only})`,
    SIAP: `SIAP (${stats.ready_pickup})`,
    SELESAI: `SELESAI (${stats.done_total || 0})`,
  };
  document.querySelectorAll(".filter-btn").forEach((b) => {
    const active = state.filter === b.dataset.filter;
    b.textContent = counts[b.dataset.filter] || b.textContent;
    b.className = active
      ? "filter-btn px-2.5 lg:px-3 py-1.5 lg:py-2 bg-[#0F2854] text-white text-xs font-bold border border-[#0F2854] whitespace-nowrap"
      : "filter-btn px-2.5 lg:px-3 py-1.5 lg:py-2 bg-[#F8FBFF] text-[#0F2854] hover:bg-[#EAF2FA] text-xs font-semibold border border-[#C9D9EA] whitespace-nowrap";
  });

  syncNavHighlight();

  const hasOverdue = overdueOrders.length > 0;
  $("delay-warning-banner").classList.toggle("hidden", !hasOverdue);
  $("notif-dot").classList.toggle("hidden", !hasOverdue);
  if (hasOverdue) {
    $("delay-warning-text").innerHTML =
      "Tiket lewat target: " +
      overdueOrders
        .map(
          (o) =>
            `<strong class="text-red-700">${o.receipt_no}</strong> (+${o.overdue_minutes} mnt)`,
        )
        .join(", ");
    $("notif-list").innerHTML = overdueOrders
      .map(
        (o) => `
      <div class="py-2 flex items-center justify-between gap-2">
        <div><span class="font-bold text-red-700">${o.receipt_no}</span> - ${o.customer_name}<div class="text-[11px] text-slate-500">Lewat ${o.overdue_minutes} mnt</div></div>
        <button type="button" onclick="openDetailModal(${o.id})" class="px-2 py-1 bg-[#0F2854] text-white text-[10px] font-bold">[CEK]</button>
      </div>
    `,
      )
      .join("");
  } else {
    $("notif-list").innerHTML =
      `<div class="py-3 text-slate-500">Tidak ada tiket melewati target waktu.</div>`;
  }
}

// Tabel Antrean & Riwayat Selesai
async function loadOrders() {
  const q = new URLSearchParams({
    route: "orders",
    search: state.search,
    filter: state.filter,
    page: state.page,
    limit: state.limit,
  });
  const { data, pagination } = await api(q.toString());
  state.page = pagination.page;

  const stepBadge = {
    2: "[2/7 ANTRE]",
    3: "[3/7 CUCI]",
    4: "[4/7 KERING]",
    5: "[5/7 SETRIKA]",
  };

  $("orders-tbody").innerHTML =
    data.length === 0
      ? `<tr><td colspan="7" class="p-8 text-center text-slate-500 font-semibold">Tidak ada tiket pesanan ditemukan.</td></tr>`
      : data
          .map((o) => {
            const s = Number(o.status_step);
            const deliv = o.delivery_type === "ANTAR_JEMPUT";
            const isKgUnit = String(o.service_unit || "Kg").toUpperCase() === "KG";
            const qtyDisplay = isKgUnit
              ? `${Number(o.weight).toFixed(1)} ${o.service_unit}`
              : `${Math.round(Number(o.weight))} ${o.service_unit}`;

            const modeHtml = deliv
              ? `<span class="inline-block px-1.5 py-0.5 bg-[#1C4D8D] text-white text-[9px] font-bold mt-1">[ANTAR-JEMPUT]</span><div class="text-[10px] text-slate-600 mt-0.5 max-w-[200px] truncate" title="${o.pickup_address || ""}">${o.pickup_address || "-"}</div>`
              : `<span class="inline-block px-1.5 py-0.5 bg-[#DCEAF7] text-slate-700 text-[9px] font-bold mt-1">[DROP-OFF]</span>`;

            const weightHtml = o.unit_label
              ? `<div class="text-[11px] text-[#0F2854] font-bold mt-0.5">${o.unit_label}</div>`
              : `<div class="text-[11px] ${Number(o.is_estimated_weight) ? "text-slate-500" : "text-[#0F2854] font-bold"} mt-0.5">${Number(o.is_estimated_weight) ? "Est. " : ""}${qtyDisplay}</div>`;

            const statusHtml =
              s === 0
                ? `<span class="inline-block px-1.5 py-0.5 bg-amber-600 text-white text-[10px] font-bold mb-1">[0/7 JEMPUT KURIR]</span><div class="text-[11px] text-amber-800 font-semibold">${o.status_note}</div>`
                : s === 1
                  ? `<span class="inline-block px-1.5 py-0.5 bg-red-700 text-white text-[10px] font-bold mb-1">[!] BUTUH TIMBANG</span><div class="text-[11px] text-red-700 font-semibold">${o.status_note}</div>`
                  : s === 6
                    ? `<span class="inline-block px-1.5 py-0.5 bg-emerald-700 text-white text-[10px] font-bold mb-1">${deliv ? "[6/7 SIAP ANTAR]" : "[6/7 SIAP AMBIL]"}</span><div class="text-[11px] text-slate-700 font-semibold">${o.status_note}</div>`
                    : s === 7
                      ? `<span class="inline-block px-1.5 py-0.5 bg-[#1C4D8D] text-white text-[10px] font-bold mb-1">[7/7 SELESAI]</span><div class="text-[11px] text-slate-600 font-semibold">${o.status_note}</div>`
                      : `<span class="inline-block px-1.5 py-0.5 bg-[#0F2854] text-white text-[10px] font-bold mb-1">${stepBadge[s] || `[${s}/7]`}</span><div class="text-[11px] text-slate-700 font-semibold">${o.status_note}</div>`;

            const payHtml = `
          <span class="inline-block px-1.5 py-0.5 ${o.payment_status === "LUNAS" ? "bg-[#0F2854] text-white" : "bg-[#DCEAF7] text-slate-700"} text-[10px] font-bold mb-0.5">${o.payment_status}</span>
          <div class="font-bold text-[#0F2854]">${o.payment_status === "LUNAS" && o.payment_method ? o.payment_method + " " : ""}${rp(o.total_price)}</div>
          ${Number(o.delivery_fee) > 0 ? `<div class="text-[10px] text-slate-500">+Ongkir ${rp(o.delivery_fee)}</div>` : ""}
        `;

            const btnHtml =
              s === 0
                ? `<button onclick="openWeighModal(${o.id})" class="px-3 py-1.5 bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold">[JEMPUT &amp; TIMBANG]</button>`
                : s === 1
                  ? `<button onclick="openWeighModal(${o.id})" class="px-3 py-1.5 bg-[#0F2854] hover:bg-[#1C4D8D] text-white text-xs font-bold">[TIMBANG]</button>`
                  : s === 6
                    ? `<div class="flex flex-wrap items-center justify-center gap-1.5">
                        <button onclick="printOrderReceipt(${o.id})" class="px-2.5 py-1.5 bg-[#0F2854] hover:bg-[#1C4D8D] text-white text-xs font-bold">[CETAK RESI]</button>
                        <button onclick="openHandoverModal(${o.id})" class="px-2.5 py-1.5 bg-[#F8FBFF] hover:bg-[#EAF2FA] text-[#0F2854] text-xs font-bold border border-[#0F2854]">${deliv ? "[ANTAR &amp; SERAHKAN]" : "[SERAHKAN]"}</button>
                      </div>`
                    : s === 7
                      ? `<div class="flex flex-wrap items-center justify-center gap-1.5">
                          <button onclick="printOrderReceipt(${o.id})" class="px-2.5 py-1.5 bg-[#0F2854] hover:bg-[#1C4D8D] text-white text-xs font-bold">[CETAK RESI]</button>
                          <button onclick="openDetailModal(${o.id})" class="px-2.5 py-1.5 bg-[#DCEAF7] hover:bg-[#C9D9EA] text-[#0F2854] text-xs font-bold border border-[#C9D9EA]">[DETAIL]</button>
                        </div>`
                      : `<button onclick="openDetailModal(${o.id})" class="px-3 py-1.5 bg-[#DCEAF7] hover:bg-[#C9D9EA] text-[#0F2854] text-xs font-bold border border-[#C9D9EA]">${s === 3 ? "[PROSES]" : s === 5 ? "[SETRIKA]" : "[DETAIL]"}</button>`;

            return `
          <tr class="hover:bg-[#F5F9FE]">
            <td class="p-4 font-extrabold border-r border-[#C9D9EA] whitespace-nowrap"><button onclick="openDetailModal(${o.id})" class="hover:underline">${o.receipt_no}</button></td>
            <td class="p-4 text-slate-600 border-r border-[#C9D9EA] whitespace-nowrap">${o.time_label}</td>
            <td class="p-4 border-r border-[#C9D9EA]"><div class="font-bold">${o.customer_name}</div><div class="text-[11px] text-slate-500">${o.customer_phone}</div>${modeHtml}</td>
            <td class="p-4 border-r border-[#C9D9EA]"><div class="font-medium">${o.service_name}</div>${weightHtml}</td>
            <td class="p-4 border-r border-[#C9D9EA]">${statusHtml}</td>
            <td class="p-4 border-r border-[#C9D9EA]">${payHtml}</td>
            <td class="p-4 text-center whitespace-nowrap">${btnHtml}</td>
          </tr>
        `;
          })
          .join("");

  const listLabel = state.filter === "SELESAI" ? "Transaksi Selesai" : "Tiket Antrean";
  $("pagination-info").innerHTML =
    `Menampilkan <strong>${pagination.from}-${pagination.to}</strong> dari <strong>${pagination.total}</strong> ${listLabel}`;
  let pages = `<button onclick="goPage(${pagination.page - 1})" ${pagination.page <= 1 ? "disabled" : ""} class="px-2.5 py-1 bg-[#F8FBFF] border border-[#C9D9EA] font-bold disabled:opacity-40">[&lt; SEBELUMNYA]</button>`;
  for (let p = 1; p <= pagination.totalPages; p++) {
    pages += `<button onclick="goPage(${p})" class="px-2.5 py-1 font-bold border ${p === pagination.page ? "bg-[#0F2854] text-white border-[#0F2854]" : "bg-[#F8FBFF] border-[#C9D9EA]"}">[${p}]</button>`;
  }
  pages += `<button onclick="goPage(${pagination.page + 1})" ${pagination.page >= pagination.totalPages ? "disabled" : ""} class="px-2.5 py-1 bg-[#F8FBFF] border border-[#C9D9EA] font-bold disabled:opacity-40">[SELANJUTNYA &gt;]</button>`;
  $("pagination-controls").innerHTML = pages;
}

window.goPage = (p) => {
  if (p >= 1) {
    state.page = p;
    loadOrders();
  }
};
const refreshAll = () => Promise.all([loadSummary(), loadOrders()]);

// Modal Create
$("btn-new-order").addEventListener("click", () => {
  $("form-create-order").reset();
  toggleCreateDelivery();
  calcCreateTotal(true);
  toggleModal("modal-create", true);
});

$("form-create-order").addEventListener("submit", async (e) => {
  e.preventDefault();
  const payload = Object.fromEntries(new FormData(e.target));
  payload.is_estimated_weight = payload.is_estimated_weight === "1";
  await api("route=orders", "POST", payload);
  toggleModal("modal-create", false);
  state.activeNav = "PESANAN";
  state.filter = "ALL";
  state.page = 1;
  await refreshAll();
});

// Modal Timbang & Konfirmasi Satuan / Setrika
window.openWeighModal = async (id) => {
  const o = await api(`route=orders&id=${id}`);
  const fee = Number(o.delivery_fee || 0);
  const unit = String(o.service_unit || "Kg");
  const isKg = unit.toUpperCase() === "KG";
  const profile = getServiceProfile(o.service_name);

  $("weigh-order-id").value = o.id;
  $("weigh-price-per-unit").value = o.price_per_unit;
  $("weigh-delivery-fee").value = fee;
  $("form-weigh-order").dataset.nextStep = String(profile.postWeighStep);

  $("weigh-modal-title").textContent = isKg
    ? `[TIMBANG ULANG // ${o.receipt_no}]`
    : `[KONFIRMASI QTY & HARGA // ${o.receipt_no}]`;

  $("weigh-customer-info").textContent =
    `${o.customer_name} (${o.customer_phone})`;
  $("weigh-service-info").textContent =
    `Layanan: ${o.service_name} (${rp(o.price_per_unit)}/${unit})`;
  $("weigh-delivery-info").textContent =
    o.delivery_type === "ANTAR_JEMPUT"
      ? `Metode: ANTAR-JEMPUT (+Ongkir ${rp(fee)}) | ${o.pickup_address}`
      : "Metode: DROP-OFF";

  const qtyInput = $("weigh-actual-input");
  $("weigh-qty-label").textContent = isKg
    ? "BERAT AKTUAL (KG) *"
    : `JUMLAH AKTUAL (${unit.toUpperCase()}) *`;
  qtyInput.step = isKg ? "0.1" : "1";
  qtyInput.min = isKg ? "0.1" : "1";
  qtyInput.value = isKg
    ? Number(o.weight).toFixed(1)
    : Math.max(1, Math.round(Number(o.weight)));

  const minTotal =
    Math.round(Number(qtyInput.value) * Number(o.price_per_unit)) + fee;
  const totalInput = $("weigh-total-input");
  totalInput.readOnly = isKg;
  totalInput.min = minTotal;
  totalInput.value = Math.max(minTotal, Number(o.total_price || minTotal));

  totalInput.className = isKg
    ? "w-full px-3 py-2 bg-[#EAF2FA] border border-[#C9D9EA] focus:ring-0 text-sm font-extrabold"
    : "w-full px-3 py-2 bg-[#F8FBFF] border-2 border-[#0F2854] focus:ring-0 text-sm font-extrabold";

  const hint = $("weigh-price-hint");
  if (hint) {
    hint.classList.toggle("hidden", isKg);
    hint.textContent = `Minimal ${rp(minTotal)} (bisa dinaikkan sesuai jenis barang).`;
  }

  $("weigh-note-input").value = profile.defaultNote;
  const submitBtn = $("form-weigh-order").querySelector('button[type="submit"]');
  if (submitBtn) {
    submitBtn.textContent = profile.isIronOnly
      ? "[KONFIRMASI & MULAI SETRIKA]"
      : "[KONFIRMASI & MULAI CUCI]";
  }

  $("weigh-pay-status").value = o.payment_status;
  $("weigh-pay-method").value = o.payment_method || "QRIS";
  toggleModal("modal-weigh", true);
};

$("weigh-actual-input").addEventListener("input", (e) => {
  const isKg = $("weigh-actual-input").step === "0.1";
  const qty = isKg
    ? parseFloat(e.target.value) || 0
    : Math.max(1, Math.round(parseFloat(e.target.value) || 0));
  const rate = Number($("weigh-price-per-unit").value);
  const fee = Number($("weigh-delivery-fee").value);
  const minTotal = Math.round(qty * rate) + fee;

  const totalInput = $("weigh-total-input");
  totalInput.min = minTotal;
  totalInput.value = minTotal;

  const hint = $("weigh-price-hint");
  if (hint && !isKg) {
    hint.textContent = `Minimal ${rp(minTotal)} (bisa dinaikkan sesuai jenis barang).`;
  }
});

$("form-weigh-order").addEventListener("submit", async (e) => {
  e.preventDefault();
  const isKg = $("weigh-actual-input").step === "0.1";
  const qty = isKg
    ? parseFloat($("weigh-actual-input").value)
    : Math.max(1, Math.round(parseFloat($("weigh-actual-input").value)));
  const minTotal =
    Math.round(qty * Number($("weigh-price-per-unit").value)) +
    Number($("weigh-delivery-fee").value);
  const finalTotal = Number($("weigh-total-input").value);

  if (finalTotal < minTotal) {
    alert(`Total tagihan tidak boleh kurang dari tarif minimal (${rp(minTotal)}).`);
    return;
  }

  const nextStep = Number($("form-weigh-order").dataset.nextStep || 3);

  await api(`route=orders&id=${$("weigh-order-id").value}`, "PUT", {
    weight: qty,
    is_estimated_weight: false,
    total_price: finalTotal,
    status_step: nextStep,
    status_note: $("weigh-note-input").value,
    payment_status: $("weigh-pay-status").value,
    payment_method: $("weigh-pay-method").value,
  });
  toggleModal("modal-weigh", false);
  await refreshAll();
});

// Modal Serahkan
window.openHandoverModal = async (id) => {
  const o = await api(`route=orders&id=${id}`);
  $("handover-order-id").value = o.id;
  $("handover-delivery-type").value = o.delivery_type;
  $("handover-modal-title").textContent =
    `${o.delivery_type === "ANTAR_JEMPUT" ? "[ANTAR & SERAHKAN" : "[SERAHKAN CUCIAN"} // ${o.receipt_no}]`;
  $("handover-summary").textContent =
    `${o.customer_name} - ${o.service_name} (${o.unit_label || o.weight + " " + o.service_unit})`;
  $("handover-delivery-info").textContent =
    o.delivery_type === "ANTAR_JEMPUT"
      ? `Antar Kurir ke: ${o.pickup_address || "-"}`
      : "Pengambilan di outlet (Drop-Off)";
  $("handover-payment-info").textContent =
    o.payment_status === "LUNAS"
      ? `Status: LUNAS (${rp(o.total_price)})`
      : `Tagihan Belum Lunas: ${rp(o.total_price)} (Dilunasi saat serah terima)`;
  $("handover-method-wrapper").classList.toggle(
    "hidden",
    o.payment_status === "LUNAS",
  );
  toggleModal("modal-handover", true);
};

$("form-handover-order").addEventListener("submit", async (e) => {
  e.preventDefault();
  const isDeliv = $("handover-delivery-type").value === "ANTAR_JEMPUT";
  await api(`route=orders&id=${$("handover-order-id").value}`, "PUT", {
    status_step: 7,
    status_note: isDeliv
      ? "Sudah Diantar ke Pelanggan"
      : "Sudah Diambil Pelanggan",
    payment_status: "LUNAS",
    payment_method: $("handover-pay-method").value,
    is_overdue: false,
  });
  toggleModal("modal-handover", false);
  await refreshAll();
});

// Modal Detail & Update (Otomatis Read-Only jika status_step === 7 & filter tahap Setrika)
window.openDetailModal = async (id) => {
  const o = await api(`route=orders&id=${id}`);
  const isCompleted = Number(o.status_step) === 7;

  $("edit-order-id").value = o.id;
  $("detail-modal-title").textContent = isCompleted
    ? `[DETAIL ARSIP TERKUNCI // ${o.receipt_no}]`
    : `[DETAIL & UPDATE // ${o.receipt_no}]`;

  $("edit-customer-name").value = o.customer_name;
  $("edit-customer-phone").value = o.customer_phone;
  $("edit-delivery-type").value = o.delivery_type || "DROP_OFF";
  $("edit-delivery-fee").value = o.delivery_fee || 0;
  $("edit-pickup-address").value = o.pickup_address || "";
  $("edit-service-select").value = o.service_id;
  $("edit-weight").value = o.weight;
  $("edit-total-price").value = o.total_price;
  $("edit-status-step").value = o.status_step;
  $("edit-status-note").value = o.status_note;
  $("edit-payment-status").value = o.payment_status;
  $("edit-payment-method").value = o.payment_method || "QRIS";
  $("edit-is-overdue").value = Number(o.is_overdue) ? "1" : "0";
  $("edit-notes").value = o.notes || "";

  syncStepSelectOptions($("edit-status-step"), o.service_name, o.status_step);

  $("edit-locked-banner")?.classList.toggle("hidden", !isCompleted);
  $("form-edit-order")
    .querySelectorAll("input:not([type='hidden']), select")
    .forEach((el) => {
      el.disabled = isCompleted;
      el.classList.toggle("bg-[#EAF2FA]", isCompleted);
      el.classList.toggle("text-slate-600", isCompleted);
      el.classList.toggle("cursor-not-allowed", isCompleted);
    });

  $("btn-delete-trigger").classList.toggle("hidden", isCompleted);
  $("btn-delete-confirm").classList.add("hidden");
  $("btn-save-edit")?.classList.toggle("hidden", isCompleted);

  toggleModal("modal-detail", true);
};

$("edit-service-select")?.addEventListener("change", (e) => {
  const opt = e.target.options[e.target.selectedIndex];
  const srvName = opt?.dataset.name || opt?.textContent || "";
  syncStepSelectOptions($("edit-status-step"), srvName, $("edit-status-step").value);
});

$("form-edit-order").addEventListener("submit", async (e) => {
  e.preventDefault();
  if ($("edit-status-step").disabled) return;

  const step = Number($("edit-status-step").value);
  await api(`route=orders&id=${$("edit-order-id").value}`, "PUT", {
    customer_name: $("edit-customer-name").value,
    customer_phone: $("edit-customer-phone").value,
    delivery_type: $("edit-delivery-type").value,
    delivery_fee: Number($("edit-delivery-fee").value || 0),
    pickup_address: $("edit-pickup-address").value,
    service_id: Number($("edit-service-select").value),
    weight: parseFloat($("edit-weight").value),
    is_estimated_weight: step <= 1,
    total_price: Number($("edit-total-price").value),
    status_step: step,
    status_note: $("edit-status-note").value,
    payment_status: $("edit-payment-status").value,
    payment_method: $("edit-payment-method").value,
    is_overdue: $("edit-is-overdue").value === "1",
    notes: $("edit-notes").value,
  });
  toggleModal("modal-detail", false);
  await refreshAll();
});

$("btn-delete-trigger").addEventListener("click", () => {
  $("btn-delete-trigger").classList.add("hidden");
  $("btn-delete-confirm").classList.remove("hidden");
});

$("btn-delete-confirm").addEventListener("click", async () => {
  await api(`route=orders&id=${$("edit-order-id").value}`, "DELETE");
  toggleModal("modal-detail", false);
  await refreshAll();
});

// Filter, Search, Popover & Navigasi Sidebar
document.querySelectorAll(".filter-btn").forEach((b) =>
  b.addEventListener("click", () => {
    state.filter = b.dataset.filter;
    state.activeNav =
      state.filter === "BARU"
        ? "TIMBANG"
        : state.filter === "SELESAI"
          ? "RIWAYAT"
          : "PESANAN";
    state.page = 1;
    refreshAll();
  }),
);

let timer;
$("search-input").addEventListener("input", (e) => {
  clearTimeout(timer);
  timer = setTimeout(() => {
    state.search = e.target.value;
    state.page = 1;
    loadOrders();
  }, 250);
});

$("btn-review-overdue").addEventListener("click", (e) => {
  e.preventDefault();
  state.filter = "OVERDUE";
  state.activeNav = "PESANAN";
  state.page = 1;
  refreshAll();
});

$("btn-notif").addEventListener("click", () => {
  $("profile-popover").classList.add("hidden");
  $("notif-popover").classList.toggle("hidden");
});
$("btn-close-notif").addEventListener("click", () =>
  $("notif-popover").classList.add("hidden"),
);
$("btn-profile").addEventListener("click", () => {
  $("notif-popover").classList.add("hidden");
  $("profile-popover").classList.toggle("hidden");
});
$("btn-close-profile").addEventListener("click", () =>
  $("profile-popover").classList.add("hidden"),
);

document.querySelectorAll(".nav-item").forEach((link) => {
  link.addEventListener("click", (e) => {
    e.preventDefault();
    const nav = link.dataset.nav;
    if (nav === "AKUN") return toggleModal("modal-account", true);
    if (nav === "LAYANAN") return openServicesModal();

    state.activeNav = nav;

    if (nav === "TIMBANG") {
      state.filter = "BARU";
    } else if (nav === "RIWAYAT") {
      state.filter = "SELESAI";
    } else {
      state.filter = "ALL";
    }

    state.page = 1;
    syncNavHighlight();
    refreshAll();

    if (nav === "RINGKASAN") {
      window.scrollTo({ top: 0, behavior: "smooth" });
    } else {
      $("orders-section").scrollIntoView({ behavior: "smooth" });
    }
  });
});

(async function init() {
  startClock();
  try {
    await api("route=orders&action=health");
    $("system-status-badge").textContent = "[ONLINE]";
  } catch {
    $("system-status-badge").textContent = "[OFFLINE]";
    $("system-status-badge").className = "font-bold text-red-700";
  }
  await loadServices();
  await refreshAll();
})();