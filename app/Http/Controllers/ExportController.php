<?php

namespace App\Http\Controllers;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Exports\EstudiantesExport;
use App\Models\Estudiante;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Writer\Pdf;

class ExportController extends Controller
{
    public function export(){
        return Excel::download(new EstudiantesExport, 'estudiantes.xlsx');
    }

    public function exportPdf()
    {
        // Generar el archivo Excel y guardarlo temporalmente
        $filePath = 'exports/estudiantes.xlsx';
        Excel::store(new EstudiantesExport, $filePath);

        // Cargar el archivo Excel desde el almacenamiento temporal
        $spreadsheet = IOFactory::load(storage_path('app/' . $filePath));

        // Convertir el archivo Excel a HTML
        $writer = IOFactory::createWriter($spreadsheet, 'Html');
        ob_start();
        $writer->save('php://output');
        $htmlContent = ob_get_clean();

        // Crear una instancia de Dompdf con opciones
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);

        // Cargar el contenido HTML en Dompdf
        $dompdf->loadHtml($htmlContent);

        // Ajustar el tamaño de la página y los márgenes
        $dompdf->setPaper('A4', 'portrait');

        // Renderizar el PDF
        $dompdf->render();

        // Configurar la respuesta HTTP para descargar el archivo PDF
        $response = response()->streamDownload(
            function () use ($dompdf) {
                echo $dompdf->output();
            },
            'estudiantes.pdf'
        );

        // Programar la eliminación del archivo temporal
        register_shutdown_function(function () use ($filePath) {
            Storage::delete($filePath);
        });

        return $response;
    }
}
