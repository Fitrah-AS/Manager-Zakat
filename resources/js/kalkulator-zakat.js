document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('public-kalkulator-form');
    if (!form) return;

    const jenisSelect = document.getElementById('calc-jenis-harta');
    const panelUmum = document.getElementById('calc-panel-umum');
    const panelPenghasilan = document.getElementById('calc-panel-penghasilan');
    const panelHaul = document.getElementById('calc-panel-haul');
    const panelEmas = document.getElementById('calc-panel-emas');
    const panelPertanian = document.getElementById('calc-panel-pertanian');

    const labelHarta = document.getElementById('calc-label-harta');
    const labelHutang = document.getElementById('calc-label-hutang');
    const infoKetentuan = document.getElementById('calc-info-ketentuan');

    const resultBox = document.getElementById('calc-result-box');
    const resultStatus = document.getElementById('calc-result-status');
    const resultNominal = document.getElementById('calc-result-nominal');
    const resultNisab = document.getElementById('calc-result-nisab');
    const resultBersih = document.getElementById('calc-result-bersih');
    const resultKadar = document.getElementById('calc-result-kadar');
    const resultCatatan = document.getElementById('calc-result-catatan');
    const errorBox = document.getElementById('calc-error-box');

    const ketentuanMap = {
        emas_perak: 'Zakat Emas & Perak: Nisab setara 85 gram emas murni dan telah dimiliki 1 tahun Hijriah (haul). Kadar zakat 2,5%.',
        penghasilan: 'Zakat Penghasilan/Profesi: Nisab tahunan setara 85 gram emas (dibagi 12 untuk perhitungan bulanan). Mendukung bruto/neto dengan kadar 2,5%.',
        perdagangan: 'Zakat Perdagangan: (Modal Diputar + Stok Barang + Piutang Lancar - Hutang Jangka Pendek) x 2,5% jika mencapai nisab 85 gram emas dan haul 1 tahun.',
        tabungan_investasi: 'Zakat Tabungan & Investasi: (Total Saldo Simpanan/Investasi - Hutang Jatuh Tempo) x 2,5% jika mencapai nisab 85 gram emas dan haul 1 tahun.',
        pertanian: 'Zakat Pertanian: Nisab 653 kg gabah kering atau 520 kg beras saat panen (tanpa syarat haul). Kadar 5% (irigasi berbiaya) atau 10% (alami/tadah hujan).',
    };

    const labelMap = {
        emas_perak: ['Total Nilai Emas / Perak (Rp)', 'Hutang Jatuh Tempo (Rp)'],
        penghasilan: ['Total Penghasilan (Rp)', 'Kebutuhan Pokok / Pengurang (Rp, khusus Neto)'],
        perdagangan: ['Total Aset Dagang Lancar & Modal (Rp)', 'Hutang Dagang Jangka Pendek (Rp)'],
        tabungan_investasi: ['Total Saldo Tabungan / Investasi (Rp)', 'Hutang Jatuh Tempo (Rp)'],
        pertanian: ['Nilai Panen (Otomatis dari Berat x Harga)', 'Biaya Operasional Panen (Rp, Opsional)'],
    };

    const syncVisibility = () => {
        const jenis = jenisSelect.value;
        const isPertanian = jenis === 'pertanian';
        const isPenghasilan = jenis === 'penghasilan';

        panelUmum.classList.toggle('hidden', isPertanian);
        panelEmas.classList.toggle('hidden', isPertanian);
        panelPertanian.classList.toggle('hidden', !isPertanian);
        panelPenghasilan.classList.toggle('hidden', !isPenghasilan);
        panelHaul.classList.toggle('hidden', isPertanian || isPenghasilan);

        if (labelMap[jenis]) {
            labelHarta.textContent = labelMap[jenis][0];
            labelHutang.textContent = labelMap[jenis][1];
        }
        if (infoKetentuan) {
            infoKetentuan.textContent = ketentuanMap[jenis] || '';
        }
    };

    jenisSelect.addEventListener('change', syncVisibility);
    syncVisibility();

    const formatRp = (val) => 'Rp ' + new Intl.NumberFormat('id-ID', { maximumFractionDigits: 0 }).format(Number(val || 0));
    const formatKg = (val) => new Intl.NumberFormat('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(Number(val || 0)) + ' kg';

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        if (errorBox) errorBox.classList.add('hidden');

        const formData = new FormData(form);
        const payload = Object.fromEntries(formData.entries());
        payload.sudah_haul = form.querySelector('#calc-sudah-haul')?.checked ? 1 : 0;

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                },
                body: JSON.stringify(payload),
            });

            const json = await response.json();
            if (!response.ok || !json.success) {
                throw new Error(json.message || 'Gagal menghitung zakat mal.');
            }

            const d = json.data;
            resultBox.classList.remove('hidden');

            if (d.wajib_zakat) {
                resultStatus.textContent = 'Memenuhi Nisab & Ketentuan — Wajib Zakat';
                resultStatus.className = 'inline-flex rounded-lg bg-emerald-100 text-emerald-800 px-3 py-1 text-xs font-bold';
            } else {
                resultStatus.textContent = 'Belum Memenuhi Nisab / Haul — Belum Wajib Zakat';
                resultStatus.className = 'inline-flex rounded-lg bg-amber-100 text-amber-800 px-3 py-1 text-xs font-bold';
            }

            if (d.jenis_harta === 'pertanian') {
                const hasilText = d.hasil_zakat_uang > 0
                    ? `${formatKg(d.hasil_zakat_kg)} atau ${formatRp(d.hasil_zakat_uang)}`
                    : formatKg(d.hasil_zakat_kg);
                resultNominal.textContent = hasilText;
                resultNisab.textContent = formatKg(d.nilai_nisab_saat_hitung);
                resultBersih.textContent = formatKg(d.berat_panen_kg) + ' panen';
            } else {
                resultNominal.textContent = formatRp(d.hasil_zakat);
                resultNisab.textContent = formatRp(d.nilai_nisab_saat_hitung);
                resultBersih.textContent = formatRp(d.harta_bersih);
            }

            resultKadar.textContent = `${d.persentase_zakat}%`;
            resultCatatan.textContent = d.catatan_perhitungan || '';
        } catch (err) {
            if (errorBox) {
                errorBox.textContent = err.message;
                errorBox.classList.remove('hidden');
            }
        }
    });
});
