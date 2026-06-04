<?php

namespace App\Exports;

use App\Models\Event;
use App\Models\Question;
use App\Models\Respondent;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class EventDetailExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle, WithEvents
{
    protected Event $event;
    protected $questions;
    protected $questionCodes = [];
    protected $rowNumber = 0;

    public function __construct(Event $event)
    {
        $this->event = $event;
        $this->questions = Question::all()->keyBy('kode_pertanyaan');
        $this->questionCodes = $this->questions->keys()->sort(SORT_NATURAL)->values()->toArray();
    }

    public function collection()
    {
        return Respondent::where('event_id', $this->event->id)
            ->orderBy('id', 'desc')
            ->get();
    }

    public function headings(): array
    {
        $baseHeaders = [
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

        // Tambahkan kolom jawaban per pertanyaan
        foreach ($this->questionCodes as $code) {
            $question = $this->questions->get($code);
            $baseHeaders[] = $code . ' - ' . ($question ? substr($question->pertanyaan, 0, 50) : $code);
        }

        return $baseHeaders;
    }

    public function map($respondent): array
    {
        $this->rowNumber++;

        $baseData = [
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

        // Tambahkan jawaban per pertanyaan
        $jawaban = $respondent->jawaban ?? [];
        foreach ($this->questionCodes as $code) {
            $ans = $jawaban[$code] ?? '-';
            if (is_array($ans)) {
                $baseData[] = $ans['jawaban'] ?? '-';
            } else {
                $baseData[] = $ans;
            }
        }

        return $baseData;
    }

    public function styles(Worksheet $sheet)
    {
        return [
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
                    'wrapText' => true,
                ],
            ],
        ];
    }

    public function title(): string
    {
        return substr($this->event->nama_event, 0, 30);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();
                $lastCol = $sheet->getHighestColumn();

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

                // Kategori column (K) conditional coloring
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
                $sheet->getRowDimension(1)->setRowHeight(35);
            },
        ];
    }
}
