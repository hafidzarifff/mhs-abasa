<?php

namespace App\Exports;

use App\Models\Event;
use App\Models\Respondent;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class DashboardExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle, WithEvents
{
    protected $latestEvent;
    protected $rowNumber = 0;

    public function __construct()
    {
        $this->latestEvent = Event::latest('tanggal')->first();
    }

    public function collection()
    {
        if (!$this->latestEvent) {
            return collect();
        }

        return Respondent::with('event')
            ->where('event_id', $this->latestEvent->id)
            ->latest()
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Waktu',
            'Nama',
            'Usia',
            'Jenis Kelamin',
            'Email',
            'No HP',
            'Instagram',
            'Sudah Follow IG',
            'Skor',
            'Kategori',
        ];
    }

    public function map($respondent): array
    {
        $this->rowNumber++;

        return [
            $this->rowNumber,
            $respondent->created_at ? $respondent->created_at->format('Y-m-d H:i') : '-',
            $respondent->nama,
            $respondent->usia,
            $respondent->jenis_kelamin,
            $respondent->email,
            $respondent->no_hp ?: '-',
            $respondent->ig ?: '-',
            isset($respondent->sudah_follow_ig) ? ($respondent->sudah_follow_ig ? 'Sudah' : 'Belum') : '-',
            $respondent->skor,
            $respondent->kategori,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();
        $lastCol = 'K';

        return [
            // Header row styling
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                    'size' => 11,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '7C3AED'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }

    public function title(): string
    {
        $eventName = $this->latestEvent ? $this->latestEvent->nama_event : 'No Data';
        return 'Dashboard - ' . substr($eventName, 0, 25);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();
                $lastCol = 'K';

                // Border for all cells
                $sheet->getStyle("A1:{$lastCol}{$lastRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'D1D5DB'],
                        ],
                    ],
                ]);

                // Alternating row colors
                for ($row = 2; $row <= $lastRow; $row++) {
                    if ($row % 2 === 0) {
                        $sheet->getStyle("A{$row}:{$lastCol}{$row}")->applyFromArray([
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'F8FAFC'],
                            ],
                        ]);
                    }
                }

                // Kategori column conditional coloring
                for ($row = 2; $row <= $lastRow; $row++) {
                    $kategori = $sheet->getCell("K{$row}")->getValue();
                    $color = match ($kategori) {
                        'Kemungkinan Kecil' => '10B981',
                        'Perlu Perhatian' => 'F59E0B',
                        'Kemungkinan Besar' => 'EF4444',
                        default => '64748B',
                    };
                    $sheet->getStyle("K{$row}")->applyFromArray([
                        'font' => [
                            'bold' => true,
                            'color' => ['rgb' => $color],
                        ],
                    ]);
                }

                // Set header row height
                $sheet->getRowDimension(1)->setRowHeight(30);
            },
        ];
    }
}
