<?php

namespace App\Exports;

use App\Models\Estudiante;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class EstudiantesExport implements FromArray, WithStyles, WithDrawings, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    /*
    public function view(): View
    {
        return view('exportar.exEstudiantes', [
            'estudiantes' => Estudiante::all()
        ]);
    }
    */

    public function array(): array
    {
        $estudiantes = Estudiante::whereHas('user', function ($query) {
            $query->where('esActivo', 1);
        })->get()->toArray();

        $data = [
            [' ', ' ', 'COLEGIO', 'Sideral Carrion'],
            [' ', ' '],
            [' ', ' ', 'Año Académico 2023-2024'],
            [' '],
            ['CÓDIGO', 'NOMBRE', 'APELLIDOS', 'DNI', 'CORREO'],
        ];

        foreach ($estudiantes as $estudiante) {
            $data[] = [
                $estudiante['codigo_estudiante'],
                $estudiante['primer_nombre'] . ' ' . $estudiante['otros_nombres'],
                $estudiante['apellido_paterno'] . ' ' . $estudiante['apellido_materno'],
                $estudiante['dni'],
                $estudiante['email']
            ];
        }

        return $data;
    }

    public function styles(Worksheet $sheet)
    {
        // Aplicar estilos
        $sheet->getStyle('A5:E5')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['argb' => 'FF0000'],
                'name' => 'Arial',
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFFF00'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'outline' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
                'inside' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]);

        $sheet->getStyle('A6:E' . ($sheet->getHighestRow()))
            ->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => '000000'],
                    ],
                ],
            ]);

        // Ajuste de ancho de columnas
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);

        return [];
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo Sideral');
        $drawing->setDescription('Colegio de Retrasados');
        $drawing->setPath(public_path('img/logo.png')); // Ruta a la imagen
        $drawing->setHeight(80);
        $drawing->setCoordinates('C1');
        return $drawing;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Combinar celdas desde C1 hasta D1
                $sheet->mergeCells('C1:D1');

                // Combinar celdas desde C3 hasta E3
                $sheet->mergeCells('C3:E3');

                // Aplicar estilos a las celdas combinadas
                $sheet->getStyle('C3:E3')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 14,
                        'color' => ['argb' => '000000'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ]);
            },
        ];
    }

    /*
    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('B5:F5')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['argb' => 'FF0000'],
                'name' => 'Arial',
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFFF00'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'outline' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
                'inside' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]);

        $sheet->getStyle('B6:F' . ($sheet->getHighestRow()))
            ->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => '000000'],
                    ],
                ],
            ]
        );

        $sheet->getStyle('C2')->applyFromArray([
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => ['argb' => '000000'],
                    'name' => 'Arial',
                ],
            ]
        );

        // Ajuste de ancho de columnas
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);

        return [];
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo Sideral');
        $drawing->setDescription('Colegio de Retrasados');
        $drawing->setPath(public_path('img/logo.png')); // Ruta a la imagen
        $drawing->setHeight(80);
        $drawing->setCoordinates('B1');
        return $drawing;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Combinar celdas desde B3 hasta F3
                $sheet->mergeCells('B3:F3');

                // Agregar texto a las celdas combinadas
                $sheet->setCellValue('B3', 'Año Académico 2023-2024');

                // Aplicar estilos a las celdas combinadas
                $sheet->getStyle('B3:F3')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 14,
                        'color' => ['argb' => '000000'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                ]);
            },
        ];
    }
    */
}
