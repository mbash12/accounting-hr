<?php

namespace App\Exports;

use App\Models\Employee;
use App\Models\ShiftSchedule;
use App\Models\ShiftType;
use Carbon\CarbonImmutable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ShiftScheduleTemplateExport implements FromArray, WithHeadings, WithTitle, WithStyles, WithColumnWidths, WithEvents
{
    public function __construct(
        public int $year,
        public int $month,
        public ?int $departmentId = null,
        public ?int $companyId = null,
        public bool $prefill = false,
        public ?int $shiftTypeId = null,
        public ?array $employeeIds = null,
    ) {}

    public function title(): string
    {
        return 'Shift Schedule';
    }

    public function headings(): array
    {
        $daysIn = (int) CarbonImmutable::create($this->year, $this->month, 1)->daysInMonth;
        $head = ['Employee ID', 'Name', 'Department'];
        foreach (range(1, $daysIn) as $d) {
            $head[] = (string) $d;
        }
        return $head;
    }

    public function array(): array
    {
        $firstDate = CarbonImmutable::create($this->year, $this->month, 1);
        $daysIn    = (int) $firstDate->daysInMonth;

        $employees = Employee::query()
            ->with(['department'])
            ->where('is_active', true)
            ->when($this->companyId, fn ($q) => $q->where('company_id', $this->companyId))
            ->when($this->employeeIds !== null, fn ($q) => $q->whereIn('id', $this->employeeIds))
            ->when($this->departmentId, fn ($q) => $q->where('department_id', $this->departmentId))
            ->when($this->shiftTypeId, function ($q) use ($firstDate) {
                $q->whereHas('shiftSchedules', function ($qq) use ($firstDate) {
                    $qq->where('shift_type_id', $this->shiftTypeId)
                        ->whereBetween('date', [$firstDate->toDateString(), $firstDate->endOfMonth()->toDateString()]);
                });
            })
            ->orderBy('name')
            ->get();

        $scheduleMap = [];
        if ($this->prefill) {
            ShiftSchedule::query()
                ->whereBetween('date', [$firstDate->toDateString(), $firstDate->endOfMonth()->toDateString()])
                ->when($this->companyId, fn ($q) => $q->where('company_id', $this->companyId))
                ->when($this->shiftTypeId, fn ($q) => $q->where('shift_type_id', $this->shiftTypeId))
                ->get()
                ->each(function ($s) use (&$scheduleMap) {
                    $scheduleMap[$s->employee_id][(int) $s->date->format('j')] = $s->shift_code;
                });
        }

        $rows = [];
        foreach ($employees as $emp) {
            $row = [(string) $emp->employee_id, $emp->name, $emp->department?->name ?? ''];
            foreach (range(1, $daysIn) as $d) {
                $row[] = $scheduleMap[$emp->id][$d] ?? '';
            }
            $rows[] = $row;
        }
        return $rows;
    }

    public function columnWidths(): array
    {
        $daysIn = (int) CarbonImmutable::create($this->year, $this->month, 1)->daysInMonth;
        $widths = [
            'A' => 18,
            'B' => 28,
            'C' => 18,
        ];
        for ($d = 1; $d <= $daysIn; $d++) {
            $col = Coordinate::stringFromColumnIndex(3 + $d);
            $widths[$col] = 5;
        }
        return $widths;
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet    = $event->sheet->getDelegate();
                $daysIn   = (int) CarbonImmutable::create($this->year, $this->month, 1)->daysInMonth;
                $firstDate = CarbonImmutable::create($this->year, $this->month, 1);
                $lastCol  = Coordinate::stringFromColumnIndex(3 + $daysIn);
                $bodyEnd  = 1 + $this->rowsCount();

                // Header row
                $sheet->getStyle("A1:{$lastCol}1")->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::VERTICAL_CENTER);

                // Day labels: just the day number, weekend columns highlighted by background color
                $sheet->getStyle("A1:{$lastCol}1")->getAlignment()
                    ->setWrapText(true)
                    ->setVertical(Alignment::VERTICAL_CENTER);
                $sheet->getRowDimension(1)->setRowHeight(22);


                // Body styling
                $sheet->getStyle("A2:{$lastCol}{$bodyEnd}")
                    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("A2:C{$bodyEnd}")
                    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

                // Freeze first 3 cols + header row
                $sheet->freezePane('D2');

                // Border seluruh tabel data
                $sheet->getStyle("A1:{$lastCol}{$bodyEnd}")->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);

                // ====== Legend di bawah data ======
                $legendStart = $bodyEnd + 2; // 1 baris kosong
                $types = ShiftType::query()
                    ->where('is_active', true)
                    ->when($this->companyId, fn ($q) => $q->where('company_id', $this->companyId))
                    ->orderBy('code')
                    ->get();

                // Legend header
                $sheet->setCellValue("A{$legendStart}", 'Legend');
                $sheet->mergeCells("A{$legendStart}:C{$legendStart}");
                $sheet->getStyle("A{$legendStart}")->getFont()->setBold(true)->setSize(12);

                $r = $legendStart + 1;
                $headers = ['Code', 'Name', 'Time'];
                foreach ($headers as $i => $h) {
                    $col = Coordinate::stringFromColumnIndex($i + 1);
                    $sheet->setCellValue("{$col}{$r}", $h);
                    $sheet->getStyle("{$col}{$r}")->getFont()->setBold(true);
                }
                // Border legend header
                $sheet->getStyle("A{$r}:C{$r}")->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);

                $r++;
                foreach ($types as $type) {
                    $sheet->setCellValue("A{$r}", $type->code);
                    $sheet->setCellValue("B{$r}", $type->name);
                    $time = $type->start_time && $type->end_time
                        ? substr($type->start_time, 0, 5) . ' – ' . substr($type->end_time, 0, 5)
                        : ($type->is_off ? 'OFF' : '—');
                    $sheet->setCellValue("C{$r}", $time);
                    // Warna background sesuai shift type
                    if ($type->color) {
                        $bg = str_replace('#', '', $type->color);
                        $sheet->getStyle("A{$r}:C{$r}")->getFill()
                            ->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()->setARGB('FF' . strtoupper($bg));
                    }
                    if ($type->text_color) {
                        $fg = str_replace('#', '', $type->text_color);
                        $sheet->getStyle("A{$r}:C{$r}")->getFont()
                            ->getColor()->setARGB('FF' . strtoupper($fg));
                    }
                    // Border legend row
                    $sheet->getStyle("A{$r}:C{$r}")->getBorders()
                        ->getAllBorders()
                        ->setBorderStyle(Border::BORDER_THIN);
                    $r++;
                }
            },
        ];
    }

    private function rowsCount(): int
    {
        $firstDate = CarbonImmutable::create($this->year, $this->month, 1);

        return Employee::query()
            ->where('is_active', true)
            ->when($this->companyId, fn ($q) => $q->where('company_id', $this->companyId))
            ->when($this->employeeIds !== null, fn ($q) => $q->whereIn('id', $this->employeeIds))
            ->when($this->departmentId, fn ($q) => $q->where('department_id', $this->departmentId))
            ->when($this->shiftTypeId, function ($q) use ($firstDate) {
                $q->whereHas('shiftSchedules', function ($qq) use ($firstDate) {
                    $qq->where('shift_type_id', $this->shiftTypeId)
                        ->whereBetween('date', [$firstDate->toDateString(), $firstDate->endOfMonth()->toDateString()]);
                });
            })
            ->count();
    }
}
