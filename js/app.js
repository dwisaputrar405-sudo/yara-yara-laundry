const API = "api/index.php";
const ROLE = document.body.dataset.userRole || "kasir";
const $ = (id) => document.getElementById(id);
const rp = (n) => "Rp " + Number(n || 0).toLocaleString("id-ID");
const pad = (n) => String(n).padStart(2, "0");
const toggleModal = (id, show) => $(id)?.classList.toggle("hidden", !show);

const state = {
  filter: "ALL",
  search: "",
  page: 1,
  limit: 5,
  services: [],
  deliveryFee: 5000,
};

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
        `<option value="${s.id}" data-price="${s.price_per_unit}">${s.name} (${rp(s.price_per_unit)}/${s.unit})</option>`,
    )
    .join("");
  $("create-service-select").innerHTML = opts;
  $("edit-service-select").innerHTML = opts;
  calcCreateTotal();

  if (ROLE === "owner" && $("services-manage-tbody")) {
    $("owner-delivery-fee-input").value = state.deliveryFee;
    $("services-manage-tbody").innerHTML = state.services
      .map(
        (s) => `
        <tr>
          <td class="p-2 border-r border-slate-300"><input id="sc-${s.id}" value="${s.code}" class="w-20 px-2 py-1 bg-slate-50 border border-slate-300 text-xs font-bold uppercase" /></td>
          <td class="p-2 border-r border-slate-300"><input id="sn-${s.id}" value="${s.name}" class="w-full px-2 py-1 bg-slate-50 border border-slate-300 text-xs font-semibold" /></td>
          <td class="p-2 border-r border-slate-300">
            <select id="su-${s.id}" class="px-2 py-1 bg-slate-50 border border-slate-300 text-xs">
              ${["Kg", "Pcs", "Meter", "Pasang"].map((u) => `<option value="${u}" ${s.unit === u ? "selected" : ""}>${u}</option>`).join("")}
            </select>
          </td>
          <td class="p-2 border-r border-slate-300"><input id="sp-${s.id}" value="${s.price_per_unit}" min="500" step="500" type="number" class="w-28 px-2 py-1 bg-slate-50 border-2 border-[#1e2430] text-xs font-extrabold" /></td>
          <td class="p-2 text-center whitespace-nowrap space-x-1">
            <button type="button" onclick="saveService(${s.id})" class="px-2.5 py-1 bg-[#1e2430] text-white text-[11px] font-bold">[SIMPAN]</button>
            <button type="button" onclick="delService(${s.id}, '${s.name}')" class="px-2 py-1 bg-white text-red-700 border border-red-700 text-[11px] font-bold">[HAPUS]</button>
          </td>
        </tr>
      `,
      )
      .join("");
  }
}

function calcCreateTotal() {
  const sel = $("create-service-select");
  const rate = Number(sel.options[sel.selectedIndex]?.dataset.price || 0);
  const w = parseFloat($("create-weight-input").value) || 0;
  const fee = Number($("create-delivery-fee").value) || 0;
  $("create-total-input").value = Math.round(w * rate) + fee;
}

function toggleCreateDelivery() {
  const isDelivery = $("create-delivery-type").value === "ANTAR_JEMPUT";
  $("create-address-wrapper").classList.toggle("hidden", !isDelivery);
  $("create-pickup-address").required = isDelivery;
  if (!isDelivery) $("create-pickup-address").value = "";
  $("create-delivery-fee").value = isDelivery ? state.deliveryFee : 0;
  $("create-status-step").value = isDelivery ? "0" : "1";
  calcCreateTotal();
}

$("create-delivery-type").addEventListener("change", toggleCreateDelivery);
$("create-service-select").addEventListener("change", calcCreateTotal);
$("create-weight-input").addEventListener("input", calcCreateTotal);

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

// Rekap & Notifikasi
async function loadSummary() {
  const { stats, overdueOrders } = await api("route=orders&action=summary");
  $("summary-total-unit").textContent = `TOTAL: ${stats.active_total} UNIT`;
  $("stat-active").textContent = pad(stats.active_total);
  $("stat-weighing").textContent = pad(stats.need_weighing);
  $("stat-process").textContent = pad(stats.in_process);
  $("stat-ready").textContent = pad(stats.ready_pickup);

  const counts = {
    ALL: `SEMUA (${stats.active_total})`,
    BARU: `BARU (${stats.need_weighing})`,
    ANTAR_JEMPUT: `ANTAR-JEMPUT (${stats.antar_jemput_total})`,
    DICUCI: `DICUCI (${stats.washing_only})`,
    SIAP: `SIAP (${stats.ready_pickup})`,
  };
  document.querySelectorAll(".filter-btn").forEach((b) => {
    const active = state.filter === b.dataset.filter;
    b.textContent = counts[b.dataset.filter];
    b.className = active
      ? "filter-btn px-3 py-2 bg-[#1e2430] text-white text-xs font-bold border border-[#1e2430]"
      : "filter-btn px-3 py-2 bg-white text-[#1e2430] hover:bg-slate-100 text-xs font-semibold border border-slate-300";
  });

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
        <button type="button" onclick="openDetailModal(${o.id})" class="px-2 py-1 bg-[#1e2430] text-white text-[10px] font-bold">[CEK]</button>
      </div>
    `,
      )
      .join("");
  } else {
    $("notif-list").innerHTML =
      `<div class="py-3 text-slate-500">Tidak ada tiket melewati target waktu.</div>`;
  }
}

// Tabel Antrean
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
            const modeHtml = deliv
              ? `<span class="inline-block px-1.5 py-0.5 bg-slate-800 text-white text-[9px] font-bold mt-1">[ANTAR-JEMPUT]</span><div class="text-[10px] text-slate-600 mt-0.5 max-w-[200px] truncate" title="${o.pickup_address || ""}">${o.pickup_address || "-"}</div>`
              : `<span class="inline-block px-1.5 py-0.5 bg-slate-200 text-slate-700 text-[9px] font-bold mt-1">[DROP-OFF]</span>`;

            const weightHtml = o.unit_label
              ? `<div class="text-[11px] text-[#1e2430] font-bold mt-0.5">${o.unit_label}</div>`
              : `<div class="text-[11px] ${Number(o.is_estimated_weight) ? "text-slate-500" : "text-[#1e2430] font-bold"} mt-0.5">${Number(o.is_estimated_weight) ? "Est. " : ""}${Number(o.weight).toFixed(1)} ${o.service_unit}</div>`;

            const statusHtml =
              s === 0
                ? `<span class="inline-block px-1.5 py-0.5 bg-amber-600 text-white text-[10px] font-bold mb-1">[0/7 JEMPUT KURIR]</span><div class="text-[11px] text-amber-800 font-semibold">${o.status_note}</div>`
                : s === 1
                  ? `<span class="inline-block px-1.5 py-0.5 bg-red-700 text-white text-[10px] font-bold mb-1">[!] BUTUH TIMBANG</span><div class="text-[11px] text-red-700 font-semibold">${o.status_note}</div>`
                  : s === 6
                    ? `<span class="inline-block px-1.5 py-0.5 bg-emerald-700 text-white text-[10px] font-bold mb-1">${deliv ? "[6/7 SIAP ANTAR]" : "[6/7 SIAP AMBIL]"}</span><div class="text-[11px] text-slate-700 font-semibold">${o.status_note}</div>`
                    : `<span class="inline-block px-1.5 py-0.5 bg-[#1e2430] text-white text-[10px] font-bold mb-1">${stepBadge[s] || `[${s}/7]`}</span><div class="text-[11px] text-slate-700 font-semibold">${o.status_note}</div>`;

            const payHtml = `
          <span class="inline-block px-1.5 py-0.5 ${o.payment_status === "LUNAS" ? "bg-[#1e2430] text-white" : "bg-slate-200 text-slate-700"} text-[10px] font-bold mb-0.5">${o.payment_status}</span>
          <div class="font-bold text-[#1e2430]">${o.payment_status === "LUNAS" && o.payment_method ? o.payment_method + " " : ""}${rp(o.total_price)}</div>
          ${Number(o.delivery_fee) > 0 ? `<div class="text-[10px] text-slate-500">+Ongkir ${rp(o.delivery_fee)}</div>` : ""}
        `;

            const btnHtml =
              s === 0
                ? `<button onclick="openWeighModal(${o.id})" class="px-3 py-1.5 bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold">[JEMPUT &amp; TIMBANG]</button>`
                : s === 1
                  ? `<button onclick="openWeighModal(${o.id})" class="px-3 py-1.5 bg-[#1e2430] hover:bg-slate-800 text-white text-xs font-bold">[TIMBANG]</button>`
                  : s === 6
                    ? `<button onclick="openHandoverModal(${o.id})" class="px-3 py-1.5 bg-white hover:bg-slate-100 text-[#1e2430] text-xs font-bold border border-[#1e2430]">${deliv ? "[ANTAR &amp; SERAHKAN]" : "[SERAHKAN]"}</button>`
                    : `<button onclick="openDetailModal(${o.id})" class="px-3 py-1.5 bg-slate-200 hover:bg-slate-300 text-[#1e2430] text-xs font-bold border border-slate-300">${s === 3 ? "[PROSES]" : "[DETAIL]"}</button>`;

            return `
          <tr class="hover:bg-slate-50">
            <td class="p-4 font-extrabold border-r border-slate-300 whitespace-nowrap"><button onclick="openDetailModal(${o.id})" class="hover:underline">${o.receipt_no}</button></td>
            <td class="p-4 text-slate-600 border-r border-slate-300 whitespace-nowrap">${o.time_label}</td>
            <td class="p-4 border-r border-slate-300"><div class="font-bold">${o.customer_name}</div><div class="text-[11px] text-slate-500">${o.customer_phone}</div>${modeHtml}</td>
            <td class="p-4 border-r border-slate-300"><div class="font-medium">${o.service_name}</div>${weightHtml}</td>
            <td class="p-4 border-r border-slate-300">${statusHtml}</td>
            <td class="p-4 border-r border-slate-300">${payHtml}</td>
            <td class="p-4 text-center whitespace-nowrap">${btnHtml}</td>
          </tr>
        `;
          })
          .join("");

  $("pagination-info").innerHTML =
    `Menampilkan <strong>${pagination.from}-${pagination.to}</strong> dari <strong>${pagination.total}</strong> Tiket Antrean`;
  let pages = `<button onclick="goPage(${pagination.page - 1})" ${pagination.page <= 1 ? "disabled" : ""} class="px-2.5 py-1 bg-white border border-slate-300 font-bold disabled:opacity-40">[&lt; SEBELUMNYA]</button>`;
  for (let p = 1; p <= pagination.totalPages; p++) {
    pages += `<button onclick="goPage(${p})" class="px-2.5 py-1 font-bold border ${p === pagination.page ? "bg-[#1e2430] text-white border-[#1e2430]" : "bg-white border-slate-300"}">[${p}]</button>`;
  }
  pages += `<button onclick="goPage(${pagination.page + 1})" ${pagination.page >= pagination.totalPages ? "disabled" : ""} class="px-2.5 py-1 bg-white border border-slate-300 font-bold disabled:opacity-40">[SELANJUTNYA &gt;]</button>`;
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
  toggleModal("modal-create", true);
});

$("form-create-order").addEventListener("submit", async (e) => {
  e.preventDefault();
  const payload = Object.fromEntries(new FormData(e.target));
  payload.is_estimated_weight = payload.is_estimated_weight === "1";
  await api("route=orders", "POST", payload);
  toggleModal("modal-create", false);
  state.filter = "ALL";
  state.page = 1;
  await refreshAll();
});

// Modal Timbang
window.openWeighModal = async (id) => {
  const o = await api(`route=orders&id=${id}`);
  const fee = Number(o.delivery_fee || 0);
  $("weigh-order-id").value = o.id;
  $("weigh-price-per-unit").value = o.price_per_unit;
  $("weigh-delivery-fee").value = fee;
  $("weigh-modal-title").textContent = `[TIMBANG ULANG // ${o.receipt_no}]`;
  $("weigh-customer-info").textContent =
    `${o.customer_name} (${o.customer_phone})`;
  $("weigh-service-info").textContent =
    `Layanan: ${o.service_name} (${rp(o.price_per_unit)}/${o.service_unit})`;
  $("weigh-delivery-info").textContent =
    o.delivery_type === "ANTAR_JEMPUT"
      ? `Metode: ANTAR-JEMPUT (+Ongkir ${rp(fee)}) | ${o.pickup_address}`
      : "Metode: DROP-OFF";
  $("weigh-actual-input").value = o.weight;
  $("weigh-total-input").value = Math.round(o.weight * o.price_per_unit) + fee;
  $("weigh-pay-status").value = o.payment_status;
  $("weigh-pay-method").value = o.payment_method || "QRIS";
  toggleModal("modal-weigh", true);
};

$("weigh-actual-input").addEventListener("input", (e) => {
  $("weigh-total-input").value =
    Math.round(
      (parseFloat(e.target.value) || 0) *
        Number($("weigh-price-per-unit").value),
    ) + Number($("weigh-delivery-fee").value);
});

$("form-weigh-order").addEventListener("submit", async (e) => {
  e.preventDefault();
  await api(`route=orders&id=${$("weigh-order-id").value}`, "PUT", {
    weight: parseFloat($("weigh-actual-input").value),
    is_estimated_weight: false,
    total_price: Number($("weigh-total-input").value),
    status_step: 3,
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

// Modal Detail & Update
window.openDetailModal = async (id) => {
  const o = await api(`route=orders&id=${id}`);
  $("edit-order-id").value = o.id;
  $("detail-modal-title").textContent = `[DETAIL & UPDATE // ${o.receipt_no}]`;
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
  $("btn-delete-trigger").classList.remove("hidden");
  $("btn-delete-confirm").classList.add("hidden");
  $("edit-order-logs").innerHTML = (o.logs || [])
    .map(
      (l) =>
        `<li>• [${l.created_at}] ${l.action} — <strong>${l.actor}</strong></li>`,
    )
    .join("");
  toggleModal("modal-detail", true);
};

$("form-edit-order").addEventListener("submit", async (e) => {
  e.preventDefault();
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

    document.querySelectorAll(".nav-item").forEach((el) => {
      el.className =
        "nav-item flex items-center gap-3 px-3 py-2.5 bg-white text-[#1e2430] hover:bg-slate-100 text-xs font-semibold tracking-wide border border-transparent hover:border-slate-300";
    });
    link.className =
      "nav-item flex items-center gap-3 px-3 py-2.5 bg-[#1e2430] text-white text-xs font-bold tracking-wide border border-[#1e2430]";

    state.filter = nav === "TIMBANG" ? "BARU" : "ALL";
    state.page = 1;
    refreshAll();
    if (nav !== "RINGKASAN")
      $("orders-section").scrollIntoView({ behavior: "smooth" });
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
