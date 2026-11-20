/**
 * Renderer grafik bar responsif berbasis SVG & Vanilla JS (tanpa ketergantungan CDN).
 */
document.addEventListener('DOMContentLoaded', () => {
    const formatRupiah = (val) => new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    }).format(val || 0);

    const formatKg = (val) => `${new Intl.NumberFormat('id-ID', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 2,
    }).format(val || 0)} kg`;

    function renderDualBarChart(containerId, payload, unitMode = 'uang') {
        const container = document.getElementById(containerId);
        if (!container || !payload) return;

        const labels = payload.labels || [];
        const values = unitMode === 'beras' ? (payload.beras || []) : (payload.uang || []);

        if (labels.length === 0 || values.every((v) => Number(v) === 0)) {
            container.innerHTML = `
                <div class="h-56 flex flex-col items-center justify-center text-center text-slate-400 border border-dashed border-slate-200 rounded-xl bg-slate-50/60 px-4">
                    <svg class="w-9 h-9 mb-2 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                    </svg>
                    <p class="text-sm font-medium text-slate-500">Belum ada data ${unitMode === 'beras' ? 'beras (kg)' : 'uang (Rp)'} yang tercatat</p>
                    <p class="text-xs text-slate-400 mt-0.5">Grafik akan otomatis tampil setelah transaksi resmi masuk ke database.</p>
                </div>
            `;
            return;
        }

        const maxVal = Math.max(...values, 1);
        const barColor = unitMode === 'beras' ? '#C9A227' : '#1B4D3E';

        const barsHtml = labels.map((label, idx) => {
            const val = Number(values[idx] || 0);
            const pct = Math.max(4, Math.round((val / maxVal) * 100));
            const formatted = unitMode === 'beras' ? formatKg(val) : formatRupiah(val);

            return `
                <div class="flex-1 min-w-[52px] flex flex-col items-center gap-2 group">
                    <div class="w-full h-44 flex items-end justify-center relative">
                        <div
                            class="w-full max-w-[44px] rounded-t-lg transition-all duration-300 group-hover:opacity-85 relative"
                            style="height: ${pct}%; background-color: ${barColor};"
                            title="${label}: ${formatted}"
                        >
                            <span class="opacity-0 group-hover:opacity-100 transition pointer-events-none absolute -top-8 left-1/2 -translate-x-1/2 whitespace-nowrap rounded bg-slate-900 text-white text-[11px] font-medium px-2 py-0.5 shadow">
                                ${formatted}
                            </span>
                        </div>
                    </div>
                    <span class="text-[11px] font-medium text-slate-600 text-center truncate w-full" title="${label}">
                        ${label}
                    </span>
                </div>
            `;
        }).join('');

        container.innerHTML = `
            <div class="h-56 flex items-end gap-3 overflow-x-auto pb-2 pt-8 px-2 border-b border-slate-100">
                ${barsHtml}
            </div>
        `;
    }

    document.querySelectorAll('[data-chart-box]').forEach((box) => {
        const raw = box.getAttribute('data-chart-payload');
        const targetId = box.getAttribute('data-chart-target');
        if (!raw || !targetId) return;

        let payload;
        try {
            payload = JSON.parse(raw);
        } catch {
            return;
        }

        let currentUnit = 'uang';
        renderDualBarChart(targetId, payload, currentUnit);

        box.querySelectorAll('[data-chart-unit]').forEach((btn) => {
            btn.addEventListener('click', () => {
                currentUnit = btn.getAttribute('data-chart-unit') || 'uang';
                box.querySelectorAll('[data-chart-unit]').forEach((b) => {
                    const active = b === btn;
                    b.classList.toggle('bg-[#1B4D3E]', active);
                    b.classList.toggle('text-white', active);
                    b.classList.toggle('bg-slate-100', !active);
                    b.classList.toggle('text-slate-600', !active);
                });
                renderDualBarChart(targetId, payload, currentUnit);
            });
        });
    });
});
