<?php

namespace App\Http\Controllers;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Exports\EstudiantesExport;
use App\Models\Asistencia;
use App\Models\Curso;
use App\Models\Curso_por_nivel;
use App\Models\Estudiante;
use App\Models\Estudiante_Seccion;
use App\Models\Notas_por_competencia;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function export()
    {
        return Excel::download(new EstudiantesExport, 'estudiantes.xlsx');
    }

    public function exportPdfEstu(Request $request)
    {
        // Obtener los filtros del request
        $filtrarPor = $request->input('filtrar_por');
        $añoIngreso = $request->input('año_ingreso');
        $buscarPor = $request->input('buscar_por');
        $fechaInicio = $request->input('fechaInicio');
        $fechaFin = $request->input('fechaFin');

        // Iniciar la consulta
        $estudiantesQuery = Estudiante::query();

        // Inicializar el título
        $titulo = 'Registro de estudiantes';

        // Aplicar filtros
        if ($filtrarPor == 'matriculado') {
            // Estudiantes matriculados
            $estudiantesQuery->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('estudiante_secciones')
                    ->whereRaw('estudiante_secciones.codigo_estudiante = estudiantes.codigo_estudiante');
            });
            $titulo = 'Registro de estudiantes matriculados';
        } elseif ($filtrarPor == 'no_matriculado') {
            // Estudiantes no matriculados
            $estudiantesQuery->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('estudiante_secciones')
                    ->whereRaw('estudiante_secciones.codigo_estudiante = estudiantes.codigo_estudiante');
            });
            $titulo = 'Registro de estudiantes no matriculados';
        }

        if ($añoIngreso) {
            $estudiantesQuery->where('año_ingreso', $añoIngreso);
            $titulo .= ' - Año de ingreso: ' . $añoIngreso;
        }

        if ($buscarPor) {
            $searchValue = null;
            switch ($buscarPor) {
                case 'codigo':
                    $searchValue = $request->input('codigo');
                    if ($searchValue) {
                        $estudiantesQuery->where('codigo_estudiante', 'like', "%$searchValue%");
                        $titulo .= ' - Código: ' . $searchValue;
                    }
                    break;
                case 'nombre':
                    $searchValue = $request->input('nombre');
                    if ($searchValue) {
                        $estudiantesQuery->where(function ($query) use ($searchValue) {
                            $query->where('primer_nombre', 'like', "%$searchValue%")
                            ->orWhere('otros_nombres', 'like', "%$searchValue%")
                            ->orWhere('apellido_paterno', 'like', "%$searchValue%")
                            ->orWhere('apellido_materno', 'like', "%$searchValue%");
                        });
                        $titulo .= ' - Nombre: ' . $searchValue;
                    }
                    break;
                case 'dni':
                    $searchValue = $request->input('dni');
                    if ($searchValue) {
                        $estudiantesQuery->where('dni', 'like', "%$searchValue%");
                        $titulo .= ' - DNI: ' . $searchValue;
                    }
                    break;
                case 'correo':
                    $searchValue = $request->input('correo');
                    if ($searchValue) {
                        $estudiantesQuery->where('email', 'like', "%$searchValue%");
                        $titulo .= ' - Correo: ' . $searchValue;
                    }
                    break;
            }
        }

        if ($fechaInicio && $fechaFin) {
            $estudiantesQuery->whereBetween('created_at', [$fechaInicio, $fechaFin]);
            $titulo .= ' - Fecha: ' . $fechaInicio . ' a ' . $fechaFin;
        }

        // Obtener los estudiantes filtrados
        $estudiantes = $estudiantesQuery->get();

        // Convertir la imagen a base64
        $path = public_path('img/logo.png');
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

        // Generar el HTML de la vista Blade, pasando el título
        $htmlContent = View::make('exportar.exEstudiantes', [
            'estudiantes' => $estudiantes,
            'base64' => $base64,
            'titulo' => $titulo,
            'filtrarPor' => $filtrarPor
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
            'estudiantes.pdf'
        );
    }


    public function exportPdfNotas(string $codigo_estudiante, string $año_escolar)
    {
        // Obtener los datos del estudiante
        $estudiante = Estudiante::where('codigo_estudiante', $codigo_estudiante)->firstOrFail();
        $estudiante_seccion = Estudiante_Seccion::where('codigo_estudiante', $codigo_estudiante)
            ->where('año_escolar', $año_escolar)
            ->firstOrFail();
        $cursos = Curso_por_nivel::where('id_nivel', $estudiante_seccion->id_nivel)
            ->whereNotIn('codigo_curso', function ($query) use ($estudiante_seccion) {
                $query->select('codigo_curso')
                ->from('exoneraciones')
                ->where('codigo_estudiante', $estudiante_seccion->codigo_estudiante)
                    ->where('año_escolar', $estudiante_seccion->año_escolar);
            })->get();
        $notas = Notas_por_competencia::where('codigo_estudiante', $estudiante->codigo_estudiante)
            ->where('año_escolar', $año_escolar)
            ->where('exoneracion', 0)->get();
        $asistencias = Asistencia::where('codigo_estudiante', $codigo_estudiante)
                                ->where('año_escolar', $año_escolar)->get();

        // Calcular promedios por curso y bimestre
        foreach ($cursos as $curso) {
            $promediosBimestre = [];
            for ($bimestre = 1; $bimestre <= 4; $bimestre++) {
                $c = 0;
                $notasBimestre = $notas->where('id_bimestre', $bimestre)->where('codigo_curso', $curso->codigo_curso);
                foreach ($notasBimestre as $verifica) {
                    if ($verifica->nivel_logro == null) {
                        $curso->{'promedio_bimestre_' . $bimestre} = "";
                        break;
                    } else {
                        $c++;
                    }
                }

                $compe = $curso->curso->competencias;
                $cantCompe = count($compe);
                if($c==$cantCompe){
                    $curso->{'promedio_bimestre_' . $bimestre} = $this->convertirPromedio($this->promedioNotas($notasBimestre));
                    $promediosBimestre[] = $this->promedioNotas($notasBimestre);
                }
            }

            // Calcular el promedio general del "Calificativo de Área" para el curso
            if (count($promediosBimestre)==4) {
                $promedioGeneral = array_sum($promediosBimestre) / count($promediosBimestre);
                $curso->promedio_general = $this->convertirPromedio($promedioGeneral);
            } else {
                $curso->promedio_general = "";
            }
            //dd(count($promediosBimestre));
        }

        // Convertir la imagen a base64
        $path = public_path('img/logo.png');
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

        // Convertir la imagen a base64
        $path2 = public_path('img/logoMinedu.png');
        $type2 = pathinfo($path2, PATHINFO_EXTENSION);
        $data2 = file_get_contents($path2);
        $base642 = 'data:image/' . $type2 . ';base64,' . base64_encode($data2);

        // Generar el HTML de la vista Blade
        $htmlContent = View::make('exportar.exBoletaNotas', [
            'estudiante' => $estudiante,
            'estudiante_seccion' => $estudiante_seccion,
            'cursos'=> $cursos,
            'notas'=>$notas,
            'asistencias'=>$asistencias,
            'base64' => $base64,
            'base642' => $base642
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

    public function exportPdfNotaProfe($codigo_curso, $nivel, $grado, $seccion){

        $estudiantes = Estudiante_Seccion::where('año_escolar', Carbon::now()->year)
            ->where('id_nivel', $nivel)
            ->where('id_grado', $grado)
            ->where('id_seccion', $seccion)
            ->whereDoesntHave('exoneraciones', function ($query) use ($codigo_curso) {
                $query->where('codigo_curso', $codigo_curso);
            })
            ->get();

        $curso = Curso::where('codigo_curso', $codigo_curso)
            ->where('esActivo', 1)
            ->first();

        $notas = Notas_por_competencia::where('año_escolar', Carbon::now()->year)
                                    ->where('exoneracion', 0)->get();

        //dd($estudiantes);

        // Convertir la imagen a base64
        $path = public_path('img/logo.png');
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

        // Convertir la imagen a base64
        $path2 = public_path('img/logoMinedu.png');
        $type2 = pathinfo($path2, PATHINFO_EXTENSION);
        $data2 = file_get_contents($path2);
        $base642 = 'data:image/' . $type2 . ';base64,' . base64_encode($data2);

        // Generar el HTML de la vista Blade
        $htmlContent = View::make('exportar.exNotasProfe', [
            'estudiantes' => $estudiantes,
            'curso' => $curso,
            'notas'=>$notas,
            'base64' => $base64,
            'base642' => $base642
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

        $dompdf->stream('reporte-notas-alumnos.pdf', ['Attachment' => false]);

        // Configurar la respuesta HTTP para descargar el archivo PDF
        return response()->streamDownload(
            function () use ($dompdf) {
                echo $dompdf->output();
            },
            'reporte-notas-alumnos.pdf'
        );
    }

    public function exportPdfAuxiliar($codigo_curso, $nivel, $grado, $seccion)
    {

        $estudiantes = Estudiante_Seccion::where('año_escolar', Carbon::now()->year)
            ->where('id_nivel', $nivel)
            ->where('id_grado', $grado)
            ->where('id_seccion', $seccion)
            ->whereDoesntHave('exoneraciones', function ($query) use ($codigo_curso) {
                $query->where('codigo_curso', $codigo_curso);
            })
            ->get();

        $curso = Curso::where('codigo_curso', $codigo_curso)
            ->where('esActivo', 1)
            ->first();

        //dd($estudiantes);

        // Convertir la imagen a base64
        $path = public_path('img/logo.png');
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

        // Convertir la imagen a base64
        $path2 = public_path('img/logoMinedu.png');
        $type2 = pathinfo($path2, PATHINFO_EXTENSION);
        $data2 = file_get_contents($path2);
        $base642 = 'data:image/' . $type2 . ';base64,' . base64_encode($data2);

        // Generar el HTML de la vista Blade
        $htmlContent = View::make('exportar.exReporteAuxiliar', [
            'estudiantes' => $estudiantes,
            'curso' => $curso,
            'base64' => $base64,
            'base642' => $base642
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
            'registro-auxiliar.pdf'
        );
    }

    function convertirNota($nota)
    {
        switch ($nota) {
            case 'AD':
                return 20;
            case 'A':
                return 15;
            case 'B':
                return 10;
            case 'C':
                return 5;
            default:
                return 0; // Valor por defecto para notas desconocidas
        }
    }

    function promedioNotas($notas)
    {
        $total = 0;
        $cantidad = count($notas);

        if ($cantidad == 0) {
            return 0; // Evitar división por cero
        }

        foreach ($notas as $nota) {
            $total += $this->convertirNota($nota->nivel_logro);
        }

        return $total / $cantidad;
    }

    function convertirPromedio($promedio)
    {
        if ($promedio >= 17.5) {
            return 'AD';
        } elseif ($promedio >= 12.5) {
            return 'A';
        } elseif ($promedio >= 9.5) {
            return 'B';
        } else {
            return 'C';
        }
    }

}
