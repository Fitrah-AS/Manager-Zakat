<?php

namespace App\Services;

use App\Enums\BentukZakatEnum;
use App\Enums\StatusPembayaranEnum;
use App\Enums\StatusPenyaluranEnum;
use App\Models\Asnaf;
use App\Models\KasTransaksi;
use App\Models\Mustahik;
use App\Models\MutasiStokBeras;
use App\Models\Muzakki;
use App\Models\Pembayaran;
use App\Models\PembayaranDetail;
use App\Models\PenyaluranDetail;
use App\Models\Periode;
use InvalidArgumentException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaporanService
{
    public const JENIS_LAPORAN = [
        'penerimaan' => [
            'judul' => 'Laporan Penerimaan Zakat',
            'deskripsi' => 'Rekapitulasi transaksi pembayaran zakat resmi berstatus terverifikasi.',
        ],
        'fitrah' => [
            'judul' => 'Laporan Zakat Fitrah',
            'deskripsi' => 'Rincian penerimaan zakat fitrah terverifikasi beserta total jiwa, beras, dan uang.',
        ],
        'mal' => [
            'judul' => 'Laporan Zakat Mal',
            'deskripsi' => 'Rincian penerimaan zakat mal terverifikasi berdasarkan jenis harta.',
        ],
        'penyaluran_asnaf' => [
            'judul' => 'Laporan Penyaluran per Asnaf',
            'deskripsi' => 'Distribusi zakat berstatus selesai yang dikelompokkan per golongan 8 asnaf.',
        ],
        'muzakki' => [
            'judul' => 'Laporan Data Muzakki',
            'deskripsi' => 'Daftar muzakki beserta total kontribusi zakat terverifikasi.',
        ],
        'mustahik' => [
            'judul' => 'Laporan Data Mustahik',
            'deskripsi' => 'Daftar mustahik, kategori asnaf, kelayakan, dan akumulasi penyaluran diterima.',
        ],
        'bku' => [
            'judul' => 'Buku Kas Umum (Rupiah)',
            'deskripsi' => 'Catatan mutasi kas masuk, kas keluar, dan saldo kas uang lembaga (Rp).',
        ],
        'stok_beras' => [
            'judul' => 'Laporan Mutasi Stok Beras (Kg)',
            'deskripsi' => 'Catatan mutasi beras masuk, beras keluar, dan saldo stok beras lembaga (kg).',
        ],
    ];

    public function generate(string $jenis, array $filters = []): array
    {
        if (! array_key_exists($jenis, self::JENIS_LAPORAN)) {
            throw new InvalidArgumentException('Jenis laporan tidak valid.');
        }

        $periodeId = $filters['periode_id'] ?? null;
        $tanggalMulai = $filters['tanggal_mulai'] ?? null;
        $tanggalSelesai = $filters['tanggal_selesai'] ?? null;
        $asnafId = $filters['asnaf_id'] ?? null;
        $keyword = trim((string) ($filters['q'] ?? ''));

        $periode = $periodeId ? Periode::find($periodeId) : null;
        $periodeLabel = $periode ? $periode->nama_periode : 'Semua Periode';

        $data = match ($jenis) {
            'penerimaan' => $this->laporanPenerimaan($periodeId, $tanggalMulai, $tanggalSelesai, $keyword),
            'fitrah' => $this->laporanFitrah($periodeId, $tanggalMulai, $tanggalSelesai, $keyword),
            'mal' => $this->laporanMal($periodeId, $tanggalMulai, $tanggalSelesai, $keyword),
            'penyaluran_asnaf' => $this->laporanPenyaluranAsnaf($periodeId, $tanggalMulai, $tanggalSelesai, $asnafId, $keyword),
            'muzakki' => $this->laporanMuzakki($periodeId, $keyword),
            'mustahik' => $this->laporanMustahik($periodeId, $asnafId, $keyword),
            'bku' => $this->laporanBku($periodeId, $tanggalMulai, $tanggalSelesai, $keyword),
            'stok_beras' => $this->laporanStokBeras($periodeId, $tanggalMulai, $tanggalSelesai, $keyword),
        };

        return array_merge([
            'jenis' => $jenis,
            'judul' => self::JENIS_LAPORAN[$jenis]['judul'],
            'deskripsi' => self::JENIS_LAPORAN[$jenis]['deskripsi'],
            'periodeLabel' => $periodeLabel,
            'tanggalMulai' => $tanggalMulai,
            'tanggalSelesai' => $tanggalSelesai,
            'rekapAsnaf' => [],
        ], $data);
    }

    public function exportExcel(string $jenis, array $filters = []): StreamedResponse
    {
        $laporan = $this->generate($jenis, $filters);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle(mb_substr($laporan['judul'], 0, 31));

        $sheet->setCellValue('A1', 'LEMBAGA AMIL ZAKAT — SISTEM INFORMASI ZAKAT FITRAH & MAL');
        $sheet->setCellValue('A2', $laporan['judul']);
        $sheet->setCellValue('A3', 'Periode: ' . $laporan['periodeLabel'] . ' | Dicetak: ' . now()->format('d/m/Y H:i') . ' WIB');

        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A3')->getFont()->setSize(10);

        $rowIndex = 5;

        if (! empty($laporan['ringkasan'])) {
            foreach ($laporan['ringkasan'] as $item) {
                $sheet->setCellValue("A{$rowIndex}", $item['label']);
                $sheet->setCellValue("B{$rowIndex}", $item['tampil']);
                $sheet->getStyle("A{$rowIndex}")->getFont()->setBold(true);
                $rowIndex++;
            }
            $rowIndex++;
        }

        $headerRow = $rowIndex;
        $colIndex = 1;
        foreach ($laporan['kolom'] as $kolom) {
            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
            $sheet->setCellValue("{$colLetter}{$headerRow}", $kolom);
            $sheet->getColumnDimension($colLetter)->setAutoSize(true);
            $colIndex++;
        }

        $lastCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(max(1, count($laporan['kolom'])));
        $sheet->getStyle("A{$headerRow}:{$lastCol}{$headerRow}")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1B4D3E']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        $rowIndex++;
        foreach ($laporan['rows'] as $row) {
            $colIndex = 1;
            foreach ($row as $cell) {
                $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex);
                $sheet->setCellValue("{$colLetter}{$rowIndex}", $cell);
                $colIndex++;
            }
            $rowIndex++;
        }

        $lastDataRow = max($headerRow, $rowIndex - 1);
        $sheet->getStyle("A{$headerRow}:{$lastCol}{$lastDataRow}")
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        $filename = 'laporan-' . $jenis . '-' . now()->format('Ymd-His') . '.xlsx';

        return response()->streamDownload(function () use ($spreadsheet): void {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    private function laporanPenerimaan($periodeId, $tanggalMulai, $tanggalSelesai, string $keyword): array
    {
        $items = Pembayaran::query()
            ->with(['muzakki', 'metodePembayaran', 'details.jenisZakat', 'verifikator'])
            ->where('status', StatusPembayaranEnum::TERVERIFIKASI->value)
            ->when($periodeId, fn ($q) => $q->where('periode_id', $periodeId))
            ->when($tanggalMulai, fn ($q) => $q->whereDate('tanggal_bayar', '>=', $tanggalMulai))
            ->when($tanggalSelesai, fn ($q) => $q->whereDate('tanggal_bayar', '<=', $tanggalSelesai))
            ->when($keyword !== '', function ($q) use ($keyword): void {
                $q->where(function ($sub) use ($keyword): void {
                    $sub->where('kode_transaksi', 'like', "%{$keyword}%")
                        ->orWhereHas('muzakki', fn ($m) => $m->where('nama_lengkap', 'like', "%{$keyword}%"));
                });
            })
            ->latest('tanggal_bayar')
            ->get();

        $totalUang = (float) $items->sum('total_uang');
        $totalBeras = (float) $items->sum('total_beras_kg');

        $rows = $items->values()->map(function ($trx, $i) {
            return [
                $i + 1,
                $trx->kode_transaksi,
                $trx->tanggal_bayar?->format('d/m/Y H:i') ?? '-',
                $trx->muzakki->nama_lengkap ?? '-',
                $trx->details->map(fn ($d) => $d->jenisZakat->nama_jenis ?? '-')->implode(', '),
                $trx->metodePembayaran->nama_metode ?? '-',
                'Rp ' . number_format((float) $trx->total_uang, 0, ',', '.'),
                number_format((float) $trx->total_beras_kg, 2, ',', '.') . ' kg',
                $trx->verifikator->name ?? '-',
            ];
        })->all();

        return [
            'ringkasan' => [
                ['label' => 'Jumlah Transaksi Terverifikasi', 'tampil' => number_format($items->count(), 0, ',', '.') . ' transaksi'],
                ['label' => 'Total Penerimaan Uang', 'tampil' => 'Rp ' . number_format($totalUang, 0, ',', '.')],
                ['label' => 'Total Penerimaan Beras', 'tampil' => number_format($totalBeras, 2, ',', '.') . ' kg'],
            ],
            'kolom' => ['No', 'Kode Transaksi', 'Tanggal', 'Muzakki', 'Jenis Zakat', 'Metode', 'Nominal Uang', 'Jumlah Beras', 'Verifikator'],
            'rows' => $rows,
        ];
    }

    private function laporanFitrah($periodeId, $tanggalMulai, $tanggalSelesai, string $keyword): array
    {
        $details = PembayaranDetail::query()
            ->with(['pembayaran.muzakki', 'pembayaran.metodePembayaran', 'jenisZakat', 'jiwa'])
            ->whereHas('jenisZakat', fn ($q) => $q->where('kategori', 'fitrah'))
            ->whereHas('pembayaran', function ($q) use ($periodeId, $tanggalMulai, $tanggalSelesai, $keyword): void {
                $q->where('status', StatusPembayaranEnum::TERVERIFIKASI->value)
                    ->when($periodeId, fn ($sub) => $sub->where('periode_id', $periodeId))
                    ->when($tanggalMulai, fn ($sub) => $sub->whereDate('tanggal_bayar', '>=', $tanggalMulai))
                    ->when($tanggalSelesai, fn ($sub) => $sub->whereDate('tanggal_bayar', '<=', $tanggalSelesai))
                    ->when($keyword !== '', function ($sub) use ($keyword): void {
                        $sub->where('kode_transaksi', 'like', "%{$keyword}%")
                            ->orWhereHas('muzakki', fn ($m) => $m->where('nama_lengkap', 'like', "%{$keyword}%"));
                    });
            })
            ->latest('id')
            ->get();

        $totalJiwa = (int) $details->sum('jumlah_jiwa');
        $totalUang = (float) $details->sum('nominal_uang');
        $totalBeras = (float) $details->sum('jumlah_beras_kg');

        $rows = $details->values()->map(function ($d, $i) {
            $anggota = $d->jiwa->pluck('nama_anggota')->implode(', ');
            return [
                $i + 1,
                $d->pembayaran->kode_transaksi ?? '-',
                $d->pembayaran->tanggal_bayar?->format('d/m/Y') ?? '-',
                $d->pembayaran->muzakki->nama_lengkap ?? '-',
                (string) ($d->jumlah_jiwa ?? 0) . ' jiwa',
                $anggota !== '' ? $anggota : '-',
                $d->bentuk_bayar->label(),
                'Rp ' . number_format((float) $d->nominal_uang, 0, ',', '.'),
                number_format((float) $d->jumlah_beras_kg, 2, ',', '.') . ' kg',
            ];
        })->all();

        return [
            'ringkasan' => [
                ['label' => 'Total Jiwa Terverifikasi', 'tampil' => number_format($totalJiwa, 0, ',', '.') . ' jiwa'],
                ['label' => 'Total Zakat Fitrah Uang', 'tampil' => 'Rp ' . number_format($totalUang, 0, ',', '.')],
                ['label' => 'Total Zakat Fitrah Beras', 'tampil' => number_format($totalBeras, 2, ',', '.') . ' kg'],
            ],
            'kolom' => ['No', 'Kode', 'Tanggal', 'Muzakki', 'Jumlah Jiwa', 'Anggota Keluarga', 'Bentuk', 'Uang (Rp)', 'Beras (Kg)'],
            'rows' => $rows,
        ];
    }

    private function laporanMal($periodeId, $tanggalMulai, $tanggalSelesai, string $keyword): array
    {
        $details = PembayaranDetail::query()
            ->with(['pembayaran.muzakki', 'jenisZakat', 'perhitunganZakatMal'])
            ->whereHas('jenisZakat', fn ($q) => $q->where('kategori', 'mal'))
            ->whereHas('pembayaran', function ($q) use ($periodeId, $tanggalMulai, $tanggalSelesai, $keyword): void {
                $q->where('status', StatusPembayaranEnum::TERVERIFIKASI->value)
                    ->when($periodeId, fn ($sub) => $sub->where('periode_id', $periodeId))
                    ->when($tanggalMulai, fn ($sub) => $sub->whereDate('tanggal_bayar', '>=', $tanggalMulai))
                    ->when($tanggalSelesai, fn ($sub) => $sub->whereDate('tanggal_bayar', '<=', $tanggalSelesai))
                    ->when($keyword !== '', function ($sub) use ($keyword): void {
                        $sub->where('kode_transaksi', 'like', "%{$keyword}%")
                            ->orWhereHas('muzakki', fn ($m) => $m->where('nama_lengkap', 'like', "%{$keyword}%"));
                    });
            })
            ->latest('id')
            ->get();

        $totalUang = (float) $details->sum('nominal_uang');
        $totalBeras = (float) $details->sum('jumlah_beras_kg');

        $rows = $details->values()->map(function ($d, $i) {
            $hitung = $d->perhitunganZakatMal;
            $hartaBersih = $hitung ? 'Rp ' . number_format((float) $hitung->harta_bersih, 0, ',', '.') : '-';
            $jenisHarta = $hitung ? str_replace('_', ' ', ucfirst($hitung->jenis_harta)) : ($d->jenisZakat->nama_jenis ?? '-');

            return [
                $i + 1,
                $d->pembayaran->kode_transaksi ?? '-',
                $d->pembayaran->tanggal_bayar?->format('d/m/Y') ?? '-',
                $d->pembayaran->muzakki->nama_lengkap ?? '-',
                $d->jenisZakat->nama_jenis ?? '-',
                $jenisHarta,
                $hartaBersih,
                'Rp ' . number_format((float) $d->nominal_uang, 0, ',', '.'),
                number_format((float) $d->jumlah_beras_kg, 2, ',', '.') . ' kg',
            ];
        })->all();

        return [
            'ringkasan' => [
                ['label' => 'Jumlah Transaksi Zakat Mal', 'tampil' => number_format($details->count(), 0, ',', '.') . ' transaksi'],
                ['label' => 'Total Zakat Mal (Uang)', 'tampil' => 'Rp ' . number_format($totalUang, 0, ',', '.')],
                ['label' => 'Total Zakat Mal Pertanian (Beras)', 'tampil' => number_format($totalBeras, 2, ',', '.') . ' kg'],
            ],
            'kolom' => ['No', 'Kode', 'Tanggal', 'Muzakki', 'Jenis Zakat', 'Jenis Harta', 'Dasar Harta Bersih', 'Nominal Uang', 'Beras (Kg)'],
            'rows' => $rows,
        ];
    }

    private function laporanPenyaluranAsnaf($periodeId, $tanggalMulai, $tanggalSelesai, $asnafId, string $keyword): array
    {
        $details = PenyaluranDetail::query()
            ->with(['penyaluran', 'mustahik', 'asnaf', 'jenisZakat'])
            ->whereHas('penyaluran', function ($q) use ($periodeId, $tanggalMulai, $tanggalSelesai, $keyword): void {
                $q->where('status', StatusPenyaluranEnum::SELESAI->value)
                    ->when($periodeId, fn ($sub) => $sub->where('periode_id', $periodeId))
                    ->when($tanggalMulai, fn ($sub) => $sub->whereDate('tanggal_penyaluran', '>=', $tanggalMulai))
                    ->when($tanggalSelesai, fn ($sub) => $sub->whereDate('tanggal_penyaluran', '<=', $tanggalSelesai))
                    ->when($keyword !== '', fn ($sub) => $sub->where('kode_penyaluran', 'like', "%{$keyword}%"));
            })
            ->when($asnafId, fn ($q) => $q->where('asnaf_id', $asnafId))
            ->when($keyword !== '', function ($q) use ($keyword): void {
                $q->whereHas('mustahik', fn ($m) => $m->where('nama_lengkap', 'like', "%{$keyword}%"));
            })
            ->latest('id')
            ->get();

        $asnafs = Asnaf::orderBy('urutan')->get();
        $rekapAsnaf = $asnafs->map(function ($asnaf) use ($details) {
            $subset = $details->where('asnaf_id', $asnaf->id);
            return [
                'nama_asnaf' => $asnaf->nama_asnaf,
                'jumlah_penerima' => $subset->count(),
                'total_uang' => (float) $subset->where('bentuk_salur', BentukZakatEnum::UANG)->sum('nominal_uang'),
                'total_beras_kg' => (float) $subset->where('bentuk_salur', BentukZakatEnum::BERAS)->sum('jumlah_beras_kg'),
            ];
        })->all();

        $totalUang = (float) $details->where('bentuk_salur', BentukZakatEnum::UANG)->sum('nominal_uang');
        $totalBeras = (float) $details->where('bentuk_salur', BentukZakatEnum::BERAS)->sum('jumlah_beras_kg');

        $rows = $details->values()->map(function ($d, $i) {
            return [
                $i + 1,
                $d->penyaluran->kode_penyaluran ?? '-',
                $d->penyaluran->tanggal_penyaluran?->format('d/m/Y') ?? '-',
                $d->mustahik->nama_lengkap ?? '-',
                $d->asnaf->nama_asnaf ?? '-',
                $d->jenisZakat->nama_jenis ?? '-',
                $d->bentuk_salur->label(),
                $d->bentuk_salur === BentukZakatEnum::UANG
                    ? 'Rp ' . number_format((float) $d->nominal_uang, 0, ',', '.')
                    : '-',
                $d->bentuk_salur === BentukZakatEnum::BERAS
                    ? number_format((float) $d->jumlah_beras_kg, 2, ',', '.') . ' kg'
                    : '-',
            ];
        })->all();

        return [
            'ringkasan' => [
                ['label' => 'Total Penerima Manfaat (Selesai)', 'tampil' => number_format($details->count(), 0, ',', '.') . ' penyaluran'],
                ['label' => 'Total Dana Tersalurkan', 'tampil' => 'Rp ' . number_format($totalUang, 0, ',', '.')],
                ['label' => 'Total Beras Tersalurkan', 'tampil' => number_format($totalBeras, 2, ',', '.') . ' kg'],
            ],
            'rekapAsnaf' => $rekapAsnaf,
            'kolom' => ['No', 'Kode Penyaluran', 'Tanggal', 'Mustahik', 'Asnaf', 'Sumber Zakat', 'Bentuk', 'Nominal Uang', 'Beras (Kg)'],
            'rows' => $rows,
        ];
    }

    private function laporanMuzakki($periodeId, string $keyword): array
    {
        $muzakkis = Muzakki::query()
            ->with(['pembayaran' => function ($q) use ($periodeId): void {
                $q->where('status', StatusPembayaranEnum::TERVERIFIKASI->value)
                    ->when($periodeId, fn ($sub) => $sub->where('periode_id', $periodeId));
            }])
            ->when($keyword !== '', function ($q) use ($keyword): void {
                $q->where(function ($sub) use ($keyword): void {
                    $sub->where('nama_lengkap', 'like', "%{$keyword}%")
                        ->orWhere('nik', 'like', "%{$keyword}%")
                        ->orWhere('no_hp', 'like', "%{$keyword}%");
                });
            })
            ->orderBy('nama_lengkap')
            ->get();

        $totalUang = 0.0;
        $totalBeras = 0.0;

        $rows = $muzakkis->values()->map(function ($m, $i) use (&$totalUang, &$totalBeras) {
            $uang = (float) $m->pembayaran->sum('total_uang');
            $beras = (float) $m->pembayaran->sum('total_beras_kg');
            $totalUang += $uang;
            $totalBeras += $beras;

            return [
                $i + 1,
                $m->nama_lengkap,
                $m->nik ?: '-',
                $m->no_hp ?: '-',
                $m->alamat ?: '-',
                $m->pembayaran->count() . 'x',
                'Rp ' . number_format($uang, 0, ',', '.'),
                number_format($beras, 2, ',', '.') . ' kg',
            ];
        })->all();

        return [
            'ringkasan' => [
                ['label' => 'Jumlah Muzakki Terdaftar', 'tampil' => number_format($muzakkis->count(), 0, ',', '.') . ' orang'],
                ['label' => 'Akumulasi Zakat Uang Terverifikasi', 'tampil' => 'Rp ' . number_format($totalUang, 0, ',', '.')],
                ['label' => 'Akumulasi Zakat Beras Terverifikasi', 'tampil' => number_format($totalBeras, 2, ',', '.') . ' kg'],
            ],
            'kolom' => ['No', 'Nama Lengkap', 'NIK', 'No. HP', 'Alamat', 'Transaksi', 'Total Uang', 'Total Beras'],
            'rows' => $rows,
        ];
    }

    private function laporanMustahik($periodeId, $asnafId, string $keyword): array
    {
        $mustahiks = Mustahik::query()
            ->with(['asnaf', 'penyaluranDetails' => function ($q) use ($periodeId): void {
                $q->whereHas('penyaluran', function ($p) use ($periodeId): void {
                    $p->where('status', StatusPenyaluranEnum::SELESAI->value)
                        ->when($periodeId, fn ($sub) => $sub->where('periode_id', $periodeId));
                });
            }])
            ->when($asnafId, fn ($q) => $q->where('asnaf_id', $asnafId))
            ->when($keyword !== '', function ($q) use ($keyword): void {
                $q->where(function ($sub) use ($keyword): void {
                    $sub->where('nama_lengkap', 'like', "%{$keyword}%")
                        ->orWhere('nik', 'like', "%{$keyword}%")
                        ->orWhere('alamat', 'like', "%{$keyword}%");
                });
            })
            ->orderBy('nama_lengkap')
            ->get();

        $jumlahLayak = $mustahiks->filter(fn ($m) => $m->status_kelayakan->value === 'layak')->count();

        $rows = $mustahiks->values()->map(function ($m, $i) {
            $uang = (float) $m->penyaluranDetails->where('bentuk_salur', BentukZakatEnum::UANG)->sum('nominal_uang');
            $beras = (float) $m->penyaluranDetails->where('bentuk_salur', BentukZakatEnum::BERAS)->sum('jumlah_beras_kg');

            return [
                $i + 1,
                $m->nama_lengkap,
                $m->nik ?: '-',
                $m->asnaf->nama_asnaf ?? '-',
                $m->status_kelayakan->label(),
                $m->no_hp ?: '-',
                $m->alamat ?: '-',
                'Rp ' . number_format($uang, 0, ',', '.'),
                number_format($beras, 2, ',', '.') . ' kg',
            ];
        })->all();

        return [
            'ringkasan' => [
                ['label' => 'Total Mustahik', 'tampil' => number_format($mustahiks->count(), 0, ',', '.') . ' orang'],
                ['label' => 'Status Layak Terima', 'tampil' => number_format($jumlahLayak, 0, ',', '.') . ' orang'],
            ],
            'kolom' => ['No', 'Nama Mustahik', 'NIK', 'Asnaf', 'Kelayakan', 'No. HP', 'Alamat', 'Diterima (Uang)', 'Diterima (Beras)'],
            'rows' => $rows,
        ];
    }

    private function laporanBku($periodeId, $tanggalMulai, $tanggalSelesai, string $keyword): array
    {
        $items = KasTransaksi::query()
            ->with(['periode', 'createdBy'])
            ->when($periodeId, fn ($q) => $q->where('periode_id', $periodeId))
            ->when($tanggalMulai, fn ($q) => $q->whereDate('tanggal_transaksi', '>=', $tanggalMulai))
            ->when($tanggalSelesai, fn ($q) => $q->whereDate('tanggal_transaksi', '<=', $tanggalSelesai))
            ->when($keyword !== '', fn ($q) => $q->where('keterangan', 'like', "%{$keyword}%"))
            ->orderBy('id')
            ->get();

        $totalMasuk = (float) $items->where('jenis_arus', 'masuk')->sum('nominal');
        $totalKeluar = (float) $items->where('jenis_arus', 'keluar')->sum('nominal');
        $saldoAkhir = (float) (KasTransaksi::latest('id')->value('saldo_setelah') ?? 0);

        $rows = $items->values()->map(function ($k, $i) {
            return [
                $i + 1,
                $k->tanggal_transaksi?->format('d/m/Y H:i') ?? '-',
                ucfirst($k->jenis_arus),
                str_replace('_', ' ', ucfirst($k->kategori)),
                $k->keterangan,
                $k->jenis_arus === 'masuk' ? 'Rp ' . number_format((float) $k->nominal, 0, ',', '.') : '-',
                $k->jenis_arus === 'keluar' ? 'Rp ' . number_format((float) $k->nominal, 0, ',', '.') : '-',
                'Rp ' . number_format((float) $k->saldo_setelah, 0, ',', '.'),
                $k->createdBy->name ?? '-',
            ];
        })->all();

        return [
            'ringkasan' => [
                ['label' => 'Total Kas Masuk (Filter)', 'tampil' => 'Rp ' . number_format($totalMasuk, 0, ',', '.')],
                ['label' => 'Total Kas Keluar (Filter)', 'tampil' => 'Rp ' . number_format($totalKeluar, 0, ',', '.')],
                ['label' => 'Saldo Kas Akhir Lembaga', 'tampil' => 'Rp ' . number_format($saldoAkhir, 0, ',', '.')],
            ],
            'kolom' => ['No', 'Tanggal', 'Arus', 'Kategori', 'Keterangan', 'Debet / Masuk (Rp)', 'Kredit / Keluar (Rp)', 'Saldo Kas (Rp)', 'Petugas'],
            'rows' => $rows,
        ];
    }

    private function laporanStokBeras($periodeId, $tanggalMulai, $tanggalSelesai, string $keyword): array
    {
        $items = MutasiStokBeras::query()
            ->with(['periode', 'createdBy'])
            ->when($periodeId, fn ($q) => $q->where('periode_id', $periodeId))
            ->when($tanggalMulai, fn ($q) => $q->whereDate('tanggal_mutasi', '>=', $tanggalMulai))
            ->when($tanggalSelesai, fn ($q) => $q->whereDate('tanggal_mutasi', '<=', $tanggalSelesai))
            ->when($keyword !== '', fn ($q) => $q->where('keterangan', 'like', "%{$keyword}%"))
            ->orderBy('id')
            ->get();

        $totalMasuk = (float) $items->where('tipe', 'masuk')->sum('jumlah_kg');
        $totalKeluar = (float) $items->where('tipe', 'keluar')->sum('jumlah_kg');
        $saldoAkhir = (float) (MutasiStokBeras::latest('id')->value('saldo_sesudah_kg') ?? 0);

        $rows = $items->values()->map(function ($m, $i) {
            return [
                $i + 1,
                $m->tanggal_mutasi?->format('d/m/Y H:i') ?? '-',
                strtoupper($m->tipe),
                $m->keterangan,
                number_format((float) $m->saldo_sebelum_kg, 2, ',', '.') . ' kg',
                $m->tipe === 'masuk' ? '+' . number_format((float) $m->jumlah_kg, 2, ',', '.') . ' kg' : '-',
                $m->tipe === 'keluar' ? '-' . number_format((float) $m->jumlah_kg, 2, ',', '.') . ' kg' : '-',
                number_format((float) $m->saldo_sesudah_kg, 2, ',', '.') . ' kg',
                $m->createdBy->name ?? '-',
            ];
        })->all();

        return [
            'ringkasan' => [
                ['label' => 'Total Beras Masuk (Filter)', 'tampil' => number_format($totalMasuk, 2, ',', '.') . ' kg'],
                ['label' => 'Total Beras Keluar (Filter)', 'tampil' => number_format($totalKeluar, 2, ',', '.') . ' kg'],
                ['label' => 'Saldo Stok Beras Saat Ini', 'tampil' => number_format($saldoAkhir, 2, ',', '.') . ' kg'],
            ],
            'kolom' => ['No', 'Tanggal', 'Tipe', 'Keterangan', 'Saldo Sebelum', 'Masuk (Kg)', 'Keluar (Kg)', 'Saldo Sesudah', 'Petugas'],
            'rows' => $rows,
        ];
    }
}
