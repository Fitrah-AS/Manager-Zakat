window.initPenyaluranForm = function initPenyaluranForm() {
    const form = document.getElementById('form-penyaluran-zakat');
    if (!form || form.dataset.penyaluranBound === '1') return;
    form.dataset.penyaluranBound = '1';

    const container = document.getElementById('penyaluran-items-container');
    const template = document.getElementById('penyaluran-row-template');
    const btnTambah = document.getElementById('btn-tambah-penerima');
    const totalUangEl = document.getElementById('summary-salur-uang');
    const totalBerasEl = document.getElementById('summary-salur-beras');

    let rowIndex = container ? container.querySelectorAll('[data-salur-row]').length : 0;

    const updateTotals = () => {
        let sumUang = 0;
        let sumBeras = 0;

        container.querySelectorAll('[data-salur-row]').forEach((row) => {
            const bentuk = row.querySelector('[data-salur-bentuk]')?.value || 'uang';
            if (bentuk === 'uang' || bentuk === 'keduanya') {
                sumUang += parseFloat(row.querySelector('[data-salur-uang]')?.value || '0');
            }
            if (bentuk === 'beras' || bentuk === 'keduanya') {
                sumBeras += parseFloat(row.querySelector('[data-salur-beras]')?.value || '0');
            }
        });

        if (totalUangEl) {
            totalUangEl.textContent = `Rp ${new Intl.NumberFormat('id-ID').format(sumUang)}`;
        }
        if (totalBerasEl) {
            totalBerasEl.textContent = `${sumBeras.toFixed(2).replace('.', ',')} kg`;
        }
    };

    const bindRow = (row) => {
        const mustahikSelect = row.querySelector('[data-salur-mustahik]');
        const asnafSelect = row.querySelector('[data-salur-asnaf]');
        const bentukSelect = row.querySelector('[data-salur-bentuk]');
        const uangBox = row.querySelector('[data-box-uang]');
        const berasBox = row.querySelector('[data-box-beras]');
        const uangInput = row.querySelector('[data-salur-uang]');
        const berasInput = row.querySelector('[data-salur-beras]');
        const removeBtn = row.querySelector('[data-remove-salur-row]');

        const syncBentuk = () => {
            const val = bentukSelect?.value || 'uang';
            const showUang = val === 'uang' || val === 'keduanya';
            const showBeras = val === 'beras' || val === 'keduanya';

            if (uangBox) uangBox.classList.toggle('hidden', !showUang);
            if (berasBox) berasBox.classList.toggle('hidden', !showBeras);
            updateTotals();
        };

        if (mustahikSelect && asnafSelect) {
            mustahikSelect.addEventListener('change', () => {
                const opt = mustahikSelect.options[mustahikSelect.selectedIndex];
                const asnafId = opt?.dataset.asnafId;
                if (asnafId) asnafSelect.value = asnafId;
            });
        }

        if (bentukSelect) bentukSelect.addEventListener('change', syncBentuk);
        if (uangInput) uangInput.addEventListener('input', updateTotals);
        if (berasInput) berasInput.addEventListener('input', updateTotals);

        if (removeBtn) {
            removeBtn.addEventListener('click', () => {
                if (container.querySelectorAll('[data-salur-row]').length <= 1) return;
                row.remove();
                updateTotals();
            });
        }

        syncBentuk();
    };

    if (container) {
        container.querySelectorAll('[data-salur-row]').forEach(bindRow);
    }

    document.querySelectorAll('[data-set-all-bentuk]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const target = btn.getAttribute('data-set-all-bentuk');
            container.querySelectorAll('[data-salur-bentuk]').forEach((select) => {
                select.value = target;
                select.dispatchEvent(new Event('change'));
            });
        });
    });

    if (btnTambah && template && container) {
        btnTambah.addEventListener('click', () => {
            const html = template.innerHTML.replace(/__INDEX__/g, String(rowIndex++));
            const wrapper = document.createElement('div');
            wrapper.innerHTML = html.trim();
            const newRow = wrapper.firstElementChild;
            if (newRow) {
                container.appendChild(newRow);
                bindRow(newRow);
                updateTotals();
            }
        });
    }
};

document.addEventListener('DOMContentLoaded', () => {
    window.initPenyaluranForm();
});
