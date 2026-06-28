<?php

namespace App\Exports;

use App\Models\Keterlambatan;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class KeterlambatanExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $kelas;

    public function __construct()
    {
        // Mengambil nama kelas walas yang sedang login (misal: "XI PPLN 1")
        $this->kelas = Auth::user()->kelas; 
    }

    /**
     * Ambil data keterlambatan berdasarkan kelas si walas
     */
    public function collection()
    {
        return Keterlambatan::with('siswa')
            ->whereHas('siswa', function ($query) {
                $query->where('kelas', $this->kelas);
            })
            ->orderBy('tanggal', 'desc')
            ->get();
    }

    /**
     * Judul Kolom (Header) Excel
     */
    public function headings(): array
    {
        return [
            'No',
            'Nama Siswa',
            'NISN',
            'Tanggal Kejadian',
            'Alasan Utama (OSIS)',
            'Keterangan Tambahan',
            'Tindakan Wali Kelas (Pembinaan)'
        ];
    }

    /**
     * Mapping data agar berurutan dan rapi per kolom
     */
    private $rowNumber = 0;
    public function map($keterlambatan): array
    {
        $this->rowNumber++;
        return [
            $this->rowNumber,
            $keterlambatan->siswa->nama ?? '-',
            $keterlambatan->siswa->nisn ?? '-',
            date('d-m-Y', strtotime($keterlambatan->tanggal)),
            $keterlambatan->alasan ?? 'Tidak ada alasan',
            $keterlambatan->keterangan ?? '-',
            $keterlambatan->penanganan ?? 'Belum ditindaklanjuti'
        ];
    }

    /**
     * Menghias, memberi warna, border, dan merapikan layout spreadsheet
     */
    public function styles(Worksheet $sheet)
    {
        $totalRows = $this->rowNumber + 1; // total baris data + 1 baris header

        // 1. Style untuk Header Utama (Baris 1)
        $sheet->getStyle('A1:G1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 11,
                'name' => 'Arial'
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1D7444'] // Warna Hijau Elegan Khas Excel
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ]
        ]);

        // Mengatur tinggi baris header biar lebih longgar
        $sheet->getRowDimension('1')->setRowHeight(28);

        // 2. Style Ringan untuk Seluruh Sel Data (Baris 2 sampai akhir)
        $sheet->getStyle("A2:G{$totalRows}")->applyFromArray([
            'font' => [
                'name' => 'Arial',
                'size' => 10,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'D1D5DB'], // Garis abu-abu tipis aesthetic
                ],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ]
        ]);

        // 3. Atur Rata Tengah (Center) untuk kolom No, NISN, dan Tanggal
        $sheet->getStyle("A2:A{$totalRows}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("C2:D{$totalRows}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Mengatur tinggi baris data agar tulisan tidak mepet kusut
        for ($i = 2; $i <= $totalRows; $i++) {
            $sheet->getRowDimension($i)->setRowHeight(22);
        }

        return [];
    }
}