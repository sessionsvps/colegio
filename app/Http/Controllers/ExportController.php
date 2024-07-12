<?php

namespace App\Http\Controllers;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Exports\EstudiantesExport;
use App\Models\Estudiante;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\View;

class ExportController extends Controller
{
    public function export(){
        return Excel::download(new EstudiantesExport, 'estudiantes.xlsx');
    }

    public function exportPdf()
    {
        // Obtener los datos de los estudiantes
        $estudiantes = Estudiante::all();

        // Convertir la imagen a base64
        $path = public_path('img/logo.png');
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

        // Generar el HTML de la vista Blade
        $htmlContent = View::make('exportar.exEstudiantes', ['estudiantes' => $estudiantes, 'base64' => $base64])->render();

        // Crear una instancia de Dompdf con opciones
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true); // Permitir recursos remotos

        $dompdf = new Dompdf($options);

        // Cargar el contenido HTML en Dompdf
        $dompdf->loadHtml($htmlContent);

        // Ajustar el tamaño de la página y los márgenes
        $dompdf->setPaper('A4', 'portrait');

        // Renderizar el PDF
        $dompdf->render();

        // Configurar la respuesta HTTP para descargar el archivo PDF
        return response()->streamDownload(
            function () use ($dompdf) {
                echo $dompdf->output();
            },
            'estudiantes.pdf'
        );
    }
}
