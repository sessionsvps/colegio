<?php

namespace App\Http\Controllers;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Exports\EstudiantesExport;
use App\Models\Curso_por_nivel;
use App\Models\Estudiante;
use App\Models\Estudiante_Seccion;
use App\Models\Notas_por_competencia;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\View;

class ExportController extends Controller
{
    public function export()
    {
        return Excel::download(new EstudiantesExport, 'estudiantes.xlsx');
    }

    public function exportPdfEstu()
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

    public function exportPdfNotas(string $codigo_estudiante)
    {
        // Obtener los datos del estudiante
        $estudiante = Estudiante::where('codigo_estudiante', $codigo_estudiante)->firstOrFail();
        $estudiante_seccion = Estudiante_Seccion::where('codigo_estudiante', $codigo_estudiante)
            ->where('año_escolar', Carbon::now()->year)
            ->firstOrFail();
        $cursos = Curso_por_nivel::where('id_nivel', $estudiante_seccion->seccion->grado->nivel->id_nivel)->get();
        $notas = Notas_por_competencia::where('codigo_estudiante', $codigo_estudiante)
                                    ->where('año_escolar', Carbon::now()->year)->get();

        // Convertir la imagen a base64
        $path = public_path('img/logo.png');
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

        // Generar el HTML de la vista Blade
        $htmlContent = View::make('exportar.exBoletaNotas', [
            'estudiante' => $estudiante,
            'estudiante_seccion' => $estudiante_seccion,
            'cursos'=> $cursos,
            'notas'=>$notas,
            'base64' => $base64
        ])->render();

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
            'libreta_notas.pdf'
        );
    }
}
