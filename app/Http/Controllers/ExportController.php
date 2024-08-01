<?php

namespace App\Http\Controllers;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Exports\EstudiantesExport;
use App\Models\Estudiante;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\View;

class ExportController extends Controller
{
    public function export(){
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

        $seccion = DB::table('estudiante_secciones')
            ->join('secciones', 'estudiante_secciones.id_seccion', '=', 'secciones.id_seccion')
            ->join('grados', 'secciones.id_grado', '=', 'grados.id_grado')
            ->join('niveles', 'grados.id_nivel', '=', 'niveles.id_nivel')
            ->where('estudiante_secciones.codigo_estudiante', $codigo_estudiante)
            ->where('estudiante_secciones.año_escolar', 2024) // Ajusta el año escolar según corresponda
            ->select('niveles.detalle as nivel', 'grados.detalle as grado', 'secciones.detalle as seccion', 'estudiante_secciones.año_escolar as año_escolar')
            ->first();

        // Convertir la imagen a base64
        $path = public_path('img/logo.png');
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

        // Generar el HTML de la vista Blade
        $htmlContent = View::make('exportar.exBoletaNotas', [
            'estudiante' => $estudiante,
            'seccion' => $seccion,
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
