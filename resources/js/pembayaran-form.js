document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('form-pembayaran-zakat');
    if (!form) return;

    const tarifUang = parseFloat(form.dataset.tarifUang || '0');
    const tarifBeras = parseFloat(form.dataset.tarifBeras || '0');

    // 1. Toggle mode muzakki (pilih vs baru)
    const modeRadios = form.querySelectorAll('input[name="mode_muzakki"]');
    const boxPilihMuzakki = document.getElementById('box-pilih-muzakki');
    const boxBaruMuzakki = document.getElementById('box-baru-muzakki');

    const syncMuzakkiMode = () => {
        const selected = form.querySelector('input[name="mode_muzakki"]:checked')?.value || 'pilih';
        if (boxPilihMuzakki) boxPilihMuzakki.classList.toggle('hidden', selected !== 'pilih');
        if (boxBaruMuzakki) boxBaruMuzakki.classList.toggle('hidden', selected !== 'baru');
    };
    modeRadios.forEach((r) => r.addEventListener('change', syncMuzakkiMode));
    syncMuzakkiMode();

    // 2. Toggle Zakat Fitrah vs Zakat Mal berdasarkan jenis_zakat_id
    const jenisSelect = document.getElementById('jenis-zakat-select');
    const boxFitrah = document.getElementById('box-zakat-fitrah');
    const boxMal = document.getElementById('box-zakat-mal');
    const jenisHartaSelect = document.getElementById('mal-jenis-harta');
    const bentukSelect = document.getElementById('bentuk-bayar-select');
    const summaryLabel = document.getElementById('ringkasan-total-pembayaran');

    const updateFitrahSummary = () => {
        const rows = document.querySelectorAll('#daftar-jiwa-container [data-jiwa-row]');
        const count = Math.max(1, rows.length);
        const bentuk = bentukSelect?.value || 'uang';

        const countDisplay = document.getElementById('jumlah-jiwa-display');
        if (countDisplay) countDisplay.textContent = `${count} Jiwa`;

        if (summaryLabel) {
            const jenisOpt = jenisSelect?.options[jenisSelect.selectedIndex];
            const kategori = jenisOpt?.dataset.kategori || 'fitrah';
            if (kategori === 'fitrah') {
                if (bentuk === 'beras') {
                    const totalKg = (count * tarifBeras).toFixed(2).replace('.', ',');
                    summaryLabel.textContent = `${totalKg} kg Beras (${count} jiwa × ${tarifBeras} kg)`;
                } else {
                    const totalRp = new Intl.NumberFormat('id-ID').format(count * tarifUang);
                    summaryLabel.textContent = `Rp ${totalRp} (${count} jiwa × Rp ${new Intl.NumberFormat('id-ID').format(tarifUang)})`;
                }
            }
        }
    };

    const syncKategoriZakat = () => {
        const opt = jenisSelect?.options[jenisSelect.selectedIndex];
        const kategori = opt?.dataset.kategori || 'fitrah';
        const kode = opt?.dataset.kode || '';

        if (boxFitrah) boxFitrah.classList.toggle('hidden', kategori !== 'fitrah');
        if (boxMal) boxMal.classList.toggle('hidden', kategori !== 'mal');

        // Otomatis pilihkan jenis harta pada kalkulator mal sesuai jenis zakat mal
        if (kategori === 'mal' && jenisHartaSelect) {
            if (kode.includes('PENGHASILAN')) jenisHartaSelect.value = 'penghasilan';
            else if (kode.includes('PERDAGANGAN')) jenisHartaSelect.value = 'perdagangan';
            else if (kode.includes('TABUNGAN')) jenisHartaSelect.value = 'tabungan_investasi';
            else if (kode.includes('PERTANIAN')) jenisHartaSelect.value = 'pertanian';
            else jenisHartaSelect.value = 'emas_perak';
            syncMalFields();
        }

        if (kategori === 'mal' && summaryLabel) {
            updateMalSummary();
        } else {
            updateFitrahSummary();
        }
    };

    // 3. Dinamis tambah/hapus anggota keluarga zakat fitrah
    const jiwaContainer = document.getElementById('daftar-jiwa-container');
    const btnTambahJiwa = document.getElementById('btn-tambah-jiwa');
    let jiwaIndex = jiwaContainer ? jiwaContainer.querySelectorAll('[data-jiwa-row]').length : 1;

    const bindRemoveRow = (row) => {
        const removeBtn = row.querySelector('[data-remove-jiwa]');
        if (!removeBtn) return;
        removeBtn.addEventListener('click', () => {
            const totalRows = jiwaContainer.querySelectorAll('[data-jiwa-row]').length;
            if (totalRows <= 1) return;
            row.remove();
            updateFitrahSummary();
        });
    };

    if (jiwaContainer) {
        jiwaContainer.querySelectorAll('[data-jiwa-row]').forEach(bindRemoveRow);
    }

    if (btnTambahJiwa && jiwaContainer) {
        btnTambahJiwa.addEventListener('click', () => {
            const row = document.createElement('div');
            row.setAttribute('data-jiwa-row', '');
            row.className = 'grid grid-cols-1 sm:grid-cols-12 gap-2.5 items-center bg-slate-50 p-2.5 rounded-lg border border-slate-200';
            row.innerHTML = `
                <div class="sm:col-span-6">
                    <input type="text" name="anggota_jiwa[${jiwaIndex}][nama_anggota]" placeholder="Nama lengkap anggota keluarga" required class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm">
                </div>
                <div class="sm:col-span-4">
                    <input type="text" name="anggota_jiwa[${jiwaIndex}][hubungan_keluarga]" placeholder="Hubungan (Istri/Anak/dll)" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm">
                </div>
                <div class="sm:col-span-2 text-right">
                    <button type="button" data-remove-jiwa class="w-full sm:w-auto rounded-lg border border-red-200 bg-red-50 hover:bg-red-100 px-3 py-2 text-xs font-semibold text-red-700 cursor-pointer">Hapus</button>
                </div>
            `;
            jiwaContainer.appendChild(row);
            jiwaIndex++;
            bindRemoveRow(row);
            updateFitrahSummary();
        });
    }

    // 4. Kalkulator bantu Zakat Mal di Form Transaksi
    const checkGunakanKalkulator = document.getElementById('gunakan-kalkulator-mal');
    const boxKalkulatorMal = document.getElementById('box-kalkulator-mal-inputs');
    const nominalMalInput = document.getElementById('nominal-uang-mal');
    const berasMalInput = document.getElementById('jumlah-beras-mal');
    const btnHitungMal = document.getElementById('btn-hitung-mal-transaksi');
    const hasilMalInfo = document.getElementById('hasil-hitung-mal-info');

    const updateMalSummary = () => {
        if (!summaryLabel) return;
        const bentuk = bentukSelect?.value || 'uang';
        if (bentuk === 'beras') {
            const kg = parseFloat(berasMalInput?.value || '0');
            summaryLabel.textContent = `${kg.toFixed(2).replace('.', ',')} kg Beras (Zakat Mal)`;
        } else {
            const rp = parseFloat(nominalMalInput?.value || '0');
            summaryLabel.textContent = `Rp ${new Intl.NumberFormat('id-ID').format(rp)} (Zakat Mal)`;
        }
    };

    const syncMalFields = () => {
        const jh = jenisHartaSelect?.value || 'emas_perak';
        document.querySelectorAll('[data-mal-only]').forEach((el) => {
            const allowed = (el.getAttribute('data-mal-only') || '').split(',');
            el.classList.toggle('hidden', !allowed.includes(jh));
        });
    };

    if (checkGunakanKalkulator && boxKalkulatorMal) {
        const syncCalcVisibility = () => {
            boxKalkulatorMal.classList.toggle('hidden', !checkGunakanKalkulator.checked);
        };
        checkGunakanKalkulator.addEventListener('change', syncCalcVisibility);
        syncCalcVisibility();
    }

    if (jenisHartaSelect) {
        jenisHartaSelect.addEventListener('change', syncMalFields);
        syncMalFields();
    }

    if (btnHitungMal) {
        btnHitungMal.addEventListener('click', () => {
            const jh = jenisHartaSelect?.value || 'emas_perak';
            const hargaEmas = parseFloat(document.getElementById('mal-harga-emas')?.value || '1500000');
            const kotor = parseFloat(document.getElementById('mal-harta-kotor')?.value || '0');
            const hutang = parseFloat(document.getElementById('mal-hutang')?.value || '0');
            const sudahHaul = document.getElementById('mal-sudah-haul')?.checked ?? true;

            let zakatRp = 0;
            let zakatKg = 0;
            let pesan = '';

            if (jh === 'pertanian') {
                const jenisPanen = document.getElementById('mal-jenis-panen')?.value || 'gabah';
                const irigasi = document.getElementById('mal-jenis-irigasi')?.value || 'berbiaya';
                const berat = parseFloat(document.getElementById('mal-berat-panen')?.value || '0');
                const hargaKg = parseFloat(document.getElementById('mal-harga-per-kg')?.value || '0');
                const nisabKg = jenisPanen === 'beras' ? 520 : 653;
                const tarif = irigasi === 'alami' ? 0.10 : 0.05;

                if (berat >= nisabKg) {
                    zakatKg = +(berat * tarif).toFixed(2);
                    const nilaiPanen = hargaKg > 0 ? Math.max(0, (berat * hargaKg) - hutang) : 0;
                    zakatRp = hargaKg > 0 ? Math.round(nilaiPanen * tarif) : 0;
                    pesan = `Memenuhi nisab ${nisabKg} kg (${tarif * 100}%): ${zakatKg} kg` + (zakatRp > 0 ? ` atau Rp ${new Intl.NumberFormat('id-ID').format(zakatRp)}` : '');
                } else {
                    pesan = `Belum mencapai nisab pertanian (${nisabKg} kg ${jenisPanen}).`;
                }
            } else if (jh === 'penghasilan') {
                const metode = document.getElementById('mal-metode-penghasilan')?.value || 'neto';
                const periode = document.getElementById('mal-periode-penghasilan')?.value || 'bulanan';
                const pengurang = metode === 'neto' ? hutang : 0;
                const bersih = Math.max(0, kotor - pengurang);
                const nisabTahun = hargaEmas * 85;
                const nisab = periode === 'bulanan' ? Math.round(nisabTahun / 12) : nisabTahun;

                if (bersih >= nisab) {
                    zakatRp = Math.round(bersih * 0.025);
                    pesan = `Memenuhi nisab ${periode} (Rp ${new Intl.NumberFormat('id-ID').format(nisab)}). Zakat 2,5% = Rp ${new Intl.NumberFormat('id-ID').format(zakatRp)}.`;
                } else {
                    pesan = `Penghasilan bersih belum mencapai nisab ${periode} (Rp ${new Intl.NumberFormat('id-ID').format(nisab)}).`;
                }
            } else {
                const bersih = Math.max(0, kotor - hutang);
                const nisab = hargaEmas * 85;
                if (bersih >= nisab && sudahHaul) {
                    zakatRp = Math.round(bersih * 0.025);
                    pesan = `Memenuhi nisab 85 gr emas (Rp ${new Intl.NumberFormat('id-ID').format(nisab)}) & haul. Zakat 2,5% = Rp ${new Intl.NumberFormat('id-ID').format(zakatRp)}.`;
                } else {
                    pesan = !sudahHaul
                        ? 'Belum memenuhi syarat haul 1 tahun.'
                        : `Harta bersih belum mencapai nisab (Rp ${new Intl.NumberFormat('id-ID').format(nisab)}).`;
                }
            }

            if (nominalMalInput && zakatRp > 0) nominalMalInput.value = zakatRp;
            if (berasMalInput && zakatKg > 0) berasMalInput.value = zakatKg;
            if (hasilMalInfo) hasilMalInfo.textContent = pesan;
            updateMalSummary();
        });
    }

    // 5. Toggle bukti transfer berdasarkan metode pembayaran (tunai vs non-tunai)
    const metodeSelect = document.getElementById('metode-pembayaran-select');
    const boxBukti = document.getElementById('box-bukti-transfer');
    const infoRekening = document.getElementById('info-rekening-metode');
    const inputBukti = document.getElementById('input-bukti-transfer');

    const syncMetode = () => {
        const opt = metodeSelect?.options[metodeSelect.selectedIndex];
        const tipe = opt?.dataset.tipe || 'tunai';
        const rekening = opt?.dataset.rekening || '';
        const isTunai = tipe === 'tunai';

        if (boxBukti) boxBukti.classList.toggle('hidden', isTunai);
        if (inputBukti) inputBukti.required = !isTunai;
        if (infoRekening) {
            infoRekening.textContent = isTunai
                ? 'Metode Tunai akan langsung berstatus Terverifikasi oleh petugas loket.'
                : (rekening ? `Tujuan: ${rekening}. Wajib unggah bukti untuk verifikasi Admin.` : 'Wajib unggah bukti transfer/QRIS untuk verifikasi Admin.');
        }
    };

    if (metodeSelect) {
        metodeSelect.addEventListener('change', syncMetode);
        syncMetode();
    }

    if (jenisSelect) jenisSelect.addEventListener('change', syncKategoriZakat);
    if (bentukSelect) bentukSelect.addEventListener('change', () => {
        updateFitrahSummary();
        updateMalSummary();
    });
    if (nominalMalInput) nominalMalInput.addEventListener('input', updateMalSummary);
    if (berasMalInput) berasMalInput.addEventListener('input', updateMalSummary);

    syncKategoriZakat();
});
