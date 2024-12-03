@extends('layouts.main')

<head>
    <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
</head>

@section('contenido')
    @if(Auth::user()->hasRole('Estudiante_Matriculado'))
        <div class="pt-10 grid grid-cols-2 gap-10">
            <div class="col-span-2 bg-yellow-500 text-white rounded-lg shadow-md overflow-hidden">
                <div class="p-6">
                    <div class="text-4xl font-bold">{{ $aula->grado->detalle }} {{$aula->detalle}} de
                        {{$aula->grado->nivel->detalle}}</div>
                    <div class="text-lg">Aula</div>
                </div>
                <a href="{{route('aulas.index')}}">
                    <div class="bg-yellow-600 p-3 text-center cursor-pointer hover:bg-yellow-700">
                        <span class="text-white">Más Información</span>
                    </div>
                </a>
            </div>

            <div class="bg-teal-500 text-white rounded-lg shadow-md overflow-hidden">
                <div class="p-6">
                    <div class="text-4xl font-bold">{{ $cantidadCursos }}</div>
                    <div class="text-lg">Cursos</div>
                </div>
                <a href="{{route('cursos.index')}}">
                    <div class="bg-teal-600 p-3 text-center cursor-pointer hover:bg-teal-700">
                        <span class="text-white">Más Información</span>
                    </div>
                </a>
            </div>

            <div class="bg-red-500 text-white rounded-lg shadow-md overflow-hidden">
                <div class="p-6">
                    <div class="text-4xl font-bold">{{ $cantidadExoneraciones }}</div>
                    <div class="text-lg">Exoneraciones</div>
                </div>
                <a href="{{route('exoneraciones.index')}}">
                    <div class="bg-red-600 p-3 text-center cursor-pointer hover:bg-red-700">
                        <span class="text-white">Más Información</span>
                    </div>
                </a>
            </div>
        </div>
    @elseif (Auth::user()->hasRole('Director') || Auth::user()->hasRole('Secretaria') || Auth::user()->hasRole('Admin'))
        <div class="flex flex-col mt-8 px-14">

            <div class="py-2 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="min-w-full rounded-lg border-b border-gray-200 shadow sm:rounded-lg bg-white">
                    <div class="my-5 md:my-10 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-0">
                        <div class="mr-0 md:ml-5">
                            <label for="nivel" class="block text-sm font-medium text-gray-700">Nivel</label>
                            <select id="nivel" name="nivel" onchange="updateGrados()" required
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="" selected disabled>Seleccione un nivel</option>
                                @foreach($niveles as $nivel)
                                    <option value="{{ $nivel->id_nivel }}" {{ request('nivel') == $nivel->id_nivel ? 'selected' : '' }}>
                                        {{ $nivel->detalle }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mr-0 md:ml-5">
                            <label for="grado" class="block text-sm font-medium text-gray-700">Grado</label>
                            <select id="grado" name="grado" required
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="" selected disabled>Seleccione un grado</option>
                            </select>
                        </div>
                        <div class="mr-0 lg:ml-5">
                            <label for="bimestre" class="block text-sm font-medium text-gray-700">Bimestre</label>
                            <select id="bimestre" name="bimestre"
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                @foreach($bimestres as $bimestre)
                                <option value="{{ $bimestre->id }}" {{ request('bimestre')==$bimestre->id ? 'selected' : '' }}>
                                    {{ $bimestre->descripcion }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mr-0 md:ml-5">
                            <label for="curso" class="block text-sm font-medium text-gray-700">Curso</label>
                            <select id="curso" name="curso" required
                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="" selected disabled>Seleccione un curso</option>
                            </select>
                        </div>
                        <div class="md:ml-5 md:mt-0 lg:col-span-1" id="botonBuscar">
                            <button id="filtrarBtn" type="button" type type="submit"
                                class="md:mt-6 inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 w-full lg:w-auto">
                                Filtrar
                            </button>
                        </div>
                    </div>
                    <div id="chartdiv3" class="h-96 mb-20">
                        <h4 class="text-xl text-center py-4 font-semibold">Notas Alumnos por Sección</h4>
                    </div>
                </div>
                <div class="min-w-full rounded-lg border-b border-gray-200 shadow sm:rounded-lg bg-white">
                    <div id="chartdiv4" class="h-96 mb-20">
                        <h4 class="text-xl text-center py-4 font-semibold">Estudiantes Matriculados</h4>
                    </div>
                </div>
                <div class="min-w-full rounded-lg border-b border-gray-200 shadow sm:rounded-lg bg-white">
                    <div id="chartdiv1" class="h-96 mb-20">
                        <h4 class="text-xl text-center py-4 font-semibold">Asistencias y Tardanzas</h4>
                    </div>
                </div>
                <div class="mt-6 min-w-full rounded-lg border-b border-gray-200 shadow sm:rounded-lg bg-white">
                    <div id="chartdiv2" class="h-60 mb-20">
                        <h4 class="text-xl text-center py-4 font-semibold">Promedio de Notas en Cursos</h4>
                    </div>
                </div>
            </div>
        </div>
    @elseif (Auth::user()->hasRole('Docente'))
        <div class="pt-10 grid grig-cols-1 md:grid-cols-2 gap-10">
            <div class="bg-teal-500 text-white rounded-lg shadow-md overflow-hidden">
                <div class="p-6">
                    <div class="text-4xl font-bold">{{ $cantidadCatedras }}</div>
                    <div class="text-lg">Cátedras</div>
                </div>
                <a href="{{route('cursos.index')}}">
                    <div class="bg-teal-600 p-3 text-center cursor-pointer hover:bg-teal-700">
                        <span class="text-white">Más Información</span>
                    </div>
                </a>
            </div>

            <div class="bg-yellow-500 text-white rounded-lg shadow-md overflow-hidden">
                <div class="p-6">
                    <div class="text-4xl font-bold">{{ $cantidadAulas }}</div>
                    <div class="text-lg">Aulas</div>
                </div>
                <a href="">
                    <div class="bg-yellow-600 p-3 text-center cursor-pointer hover:bg-yellow-700">
                        <span class="text-white">Más Información</span>
                    </div>
                </a>
            </div>
        </div>
    @endif
@endsection

@section('scripts')
    <script>
        let chart3;
        am5.ready(function() {
            //var userId = "{{ Auth::user()->id }}"; // Obtener el ID del usuario autenticado

            // Create root element for first chart
            var root3 = am5.Root.new("chartdiv3");

            // Set themes
            root3.setThemes([
                am5themes_Animated.new(root3)
            ]);

            // Create chart
            chart3 = root3.container.children.push(am5xy.XYChart.new(root3, {
                panX: false,
                panY: false,
                paddingLeft: 0,
                wheelX: "panX",
                wheelY: "zoomX",
                layout: root3.verticalLayout
            }));

            // Add legend
            var legend3 = chart3.children.push(
                am5.Legend.new(root3, {
                    centerX: am5.p50,
                    x: am5.p50
                })
            );

            // Load data from API for chartdiv3
            am5.net.load(`http://127.0.0.1:8000/api/asistencias`).then(function(result) {
                var response = am5.JSONParser.parse(result.response);
                var logrosPorGradoSeccion = response.logrosPorGradoSeccion;
                console.log(logrosPorGradoSeccion);  // Log para verificar la estructura de los datos

                // Create axes
                var xRenderer3 = am5xy.AxisRendererX.new(root3, {
                    cellStartLocation: 0.1,
                    cellEndLocation: 0.9,
                    minorGridEnabled: true
                });

                var xAxis3 = chart3.xAxes.push(am5xy.CategoryAxis.new(root3, {
                    categoryField: "grado_seccion",
                    renderer: xRenderer3,
                    tooltip: am5.Tooltip.new(root3, {})
                }));

                xRenderer3.grid.template.setAll({
                    location: 1
                });

                xAxis3.data.setAll(logrosPorGradoSeccion);

                var yAxis3 = chart3.yAxes.push(am5xy.ValueAxis.new(root3, {
                    renderer: am5xy.AxisRendererY.new(root3, {
                        strokeOpacity: 0.1
                    })
                }));

                // Function to create series for each achievement level in chartdiv3
                function makeSeries3(name, fieldName) {
                    var series = chart3.series.push(am5xy.ColumnSeries.new(root3, {
                        name: name,
                        xAxis: xAxis3,
                        yAxis: yAxis3,
                        valueYField: fieldName,
                        categoryXField: "grado_seccion"
                    }));

                    series.columns.template.setAll({
                        tooltipText: "{name}, {categoryX}: {valueY}",
                        width: am5.percent(90),
                        tooltipY: 0,
                        strokeOpacity: 0
                    });

                    series.data.setAll(logrosPorGradoSeccion);

                    // Add labels for each column
                    series.bullets.push(function () {
                        return am5.Bullet.new(root3, {
                            locationY: 0,
                            sprite: am5.Label.new(root3, {
                                text: "{valueY}",
                                fill: root3.interfaceColors.get("alternativeText"),
                                centerY: 0,
                                centerX: am5.p50,
                                populateText: true
                            })
                        });
                    });

                    legend3.data.push(series);
                }

                // Create a series for each achievement level
                makeSeries3("A", "nivel_logro_A");
                makeSeries3("AD", "nivel_logro_AD");
                makeSeries3("B", "nivel_logro_B");
                makeSeries3("C", "nivel_logro_C");

                // Make chart appear on load
                chart3.appear(1000, 100);
            }).catch(function(result) {
                console.log("Error loading " + result.xhr.responseURL);
            });



            // Create root element for second chart
            var root1 = am5.Root.new("chartdiv1");

            // Set themes
            root1.setThemes([
                am5themes_Animated.new(root1)
            ]);

            // Create chart
            var chart1 = root1.container.children.push(am5xy.XYChart.new(root1, {
                panX: false,
                panY: false,
                paddingLeft: 0,
                wheelX: "panX",
                wheelY: "zoomX",
                layout: root1.verticalLayout
            }));

            // Add legend
            var legend1 = chart1.children.push(
                am5.Legend.new(root1, {
                    centerX: am5.p50,
                    x: am5.p50
                })
            );

            // Load data from API for first chart
            am5.net.load(`http://127.0.0.1:8000/api/asistencias`).then(function(result) {
                var response = am5.JSONParser.parse(result.response);
                var asistencias = response.asistencias;
                console.log(asistencias);  // Log para verificar la estructura de los datos

                // Create axes
                var xRenderer1 = am5xy.AxisRendererX.new(root1, {
                    cellStartLocation: 0.1,
                    cellEndLocation: 0.9,
                    minorGridEnabled: true
                });

                var xAxis1 = chart1.xAxes.push(am5xy.CategoryAxis.new(root1, {
                    categoryField: "bimestre",
                    renderer: xRenderer1,
                    tooltip: am5.Tooltip.new(root1, {})
                }));

                xRenderer1.grid.template.setAll({
                    location: 1
                });

                xAxis1.data.setAll(asistencias);

                var yAxis1 = chart1.yAxes.push(am5xy.ValueAxis.new(root1, {
                    renderer: am5xy.AxisRendererY.new(root1, {
                        strokeOpacity: 0.1
                    })
                }));

                // Function to create series for the first chart
                function makeSeries1(name, fieldName) {
                    var series = chart1.series.push(am5xy.ColumnSeries.new(root1, {
                        name: name,
                        xAxis: xAxis1,
                        yAxis: yAxis1,
                        valueYField: fieldName,
                        categoryXField: "bimestre"
                    }));

                    series.columns.template.setAll({
                        tooltipText: "{name}, {categoryX}:{valueY}",
                        width: am5.percent(90),
                        tooltipY: 0,
                        strokeOpacity: 0
                    });

                    series.data.setAll(asistencias);

                    // Make stuff animate on load
                    series.appear();

                    series.bullets.push(function () {
                        return am5.Bullet.new(root1, {
                            locationY: 0,
                            sprite: am5.Label.new(root1, {
                                text: "{valueY}",
                                fill: root1.interfaceColors.get("alternativeText"),
                                centerY: 0,
                                centerX: am5.p50,
                                populateText: true
                            })
                        });
                    });

                    legend1.data.push(series);
                }

                // Create series for each category in the first chart
                makeSeries1("Inasistencias Justificadas", "inasistencias_justificadas");
                makeSeries1("Inasistencias Injustificadas", "inasistencias_injustificadas");
                makeSeries1("Tardanzas Justificadas", "tardanzas_justificadas");
                makeSeries1("Tardanzas Injustificadas", "tardanzas_injustificadas");

                // Make stuff animate on load
                chart1.appear(1000, 100);
            }).catch(function(result) {
                console.log("Error loading " + result.xhr.responseURL);
            });

            // Create root element for third chart
            var root2 = am5.Root.new("chartdiv2");

            // Set themes
            root2.setThemes([
                am5themes_Animated.new(root2)
            ]);

            // Create chart
            var chart2 = root2.container.children.push(
                am5percent.PieChart.new(root2, {
                    endAngle: 270
                })
            );

            // Create series
            var series2 = chart2.series.push(
                am5percent.PieSeries.new(root2, {
                    valueField: "total",
                    categoryField: "nivel_logro",
                    endAngle: 270
                })
            );

            series2.states.create("hidden", {
                endAngle: -90
            });

            // Load data from API for second chart
            am5.net.load(`http://127.0.0.1:8000/api/asistencias`).then(function(result) {
                var response = am5.JSONParser.parse(result.response);
                var logros = response.logros;
                console.log(logros);  // Log para verificar la estructura de los datos

                // Set data
                series2.data.setAll(logros);

                series2.appear(1000, 100);
            }).catch(function(result) {
                console.log("Error loading " + result.xhr.responseURL);
            });

        }); // end am5.ready()
    </script>
    <script>
        document.getElementById('nivel').addEventListener('change', function() {
            updateGrados();
            updateCursos();
        });

        function updateGrados() {
            const nivel = document.getElementById('nivel').value;
            const grados = @json(['primaria' => $grados_primaria, 'secundaria' => $grados_secundaria]);

            const gradoSelect = document.getElementById('grado');
            gradoSelect.innerHTML = '<option value="" selected disabled>Seleccione un grado</option>'; // Reset opciones

            if (nivel == 1) { // Primaria
                grados.primaria.forEach(grado => {
                    const option = document.createElement('option');
                    option.value = grado.id_grado;
                    option.textContent = grado.detalle;
                    gradoSelect.appendChild(option);
                });
            } else if (nivel == 2) { // Secundaria
                grados.secundaria.forEach(grado => {
                    const option = document.createElement('option');
                    option.value = grado.id_grado;
                    option.textContent = grado.detalle;
                    gradoSelect.appendChild(option);
                });
            }
        }

        function updateCursos() {
        const nivel = document.getElementById('nivel').value;
        const cursos = @json($cursosPorNivel); // Supón que este JSON tiene todos los cursos agrupados

        const cursoSelect = document.getElementById('curso');
        cursoSelect.innerHTML = '<option value="" selected disabled>Seleccione un curso</option>'; // Reset opciones

        if (nivel && cursos[nivel]) {
            cursos[nivel].forEach(curso => {
                const option = document.createElement('option');
                option.value = curso.id_curso;
                option.textContent = curso.nombre_curso;
                cursoSelect.appendChild(option);
            });
        }
    }
    </script>

    <script>
        document.getElementById('filtrarBtn').addEventListener('click', function () {
            const nivel = document.getElementById('nivel').value;
            const grado = document.getElementById('grado').value;
            const bimestre = document.getElementById('bimestre').value;
            const curso = document.getElementById('curso').value;

            // Verifica que todos los campos tengan un valor
            if (!nivel || !grado || !bimestre || !curso) {
                alertify.alert('Advertencia', 'Por favor, complete todos los campos antes de filtrar.');
                return;
            }

            // Construye la URL con los parámetros seleccionados
            const apiUrl = `/api/asistencias?nivel=${nivel}&grado=${grado}&bimestre=${bimestre}&curso=${curso}`;

            // Llama a la API para obtener los datos
            fetch(apiUrl)
                .then((response) => response.json())
                .then((data) => {
                    const logrosPorGradoSeccion = data.logrosPorGradoSeccion;

                    // Actualizar el gráfico de "Notas Alumnos por Sección"
                    actualizarGraficoNotasPorSeccion(logrosPorGradoSeccion);
                })
                .catch((error) => {
                    console.error('Error al cargar los datos:', error);
                });
        });

        function actualizarGraficoNotasPorSeccion(data) {
            // Verifica que chart3 esté inicializado
            if (!chart3) {
                console.error("El gráfico aún no está inicializado.");
                return;
            }

            // Limpia las series existentes
            chart3.series.clear();

            // Configurar los ejes
            const xAxis = chart3.xAxes.getIndex(0);
            const yAxis = chart3.yAxes.getIndex(0);

            if (xAxis) xAxis.data.setAll(data);
            if (yAxis) yAxis.data.setAll(data);

            // Crear nuevas series con los datos actualizados
            function makeSeries(name, fieldName) {
                const series = chart3.series.push(
                    am5xy.ColumnSeries.new(chart3._root, {
                        name: name,
                        xAxis: xAxis,
                        yAxis: yAxis,
                        valueYField: fieldName,
                        categoryXField: "grado_seccion",
                    })
                );

                series.columns.template.setAll({
                    tooltipText: "{name}, {categoryX}: {valueY}",
                    width: am5.percent(90),
                    tooltipY: 0,
                    strokeOpacity: 0,
                });

                series.data.setAll(data);

                // Añadir etiquetas a las columnas
                series.bullets.push(function () {
                    return am5.Bullet.new(chart3._root, {
                        locationY: 0,
                        sprite: am5.Label.new(chart3._root, {
                            text: "{valueY}",
                            fill: chart3._root.interfaceColors.get("alternativeText"),
                            centerY: 0,
                            centerX: am5.p50,
                            populateText: true,
                        }),
                    });
                });
            }

            // Crear series para cada nivel de logro
            makeSeries("A", "nivel_logro_A");
            makeSeries("AD", "nivel_logro_AD");
            makeSeries("B", "nivel_logro_B");
            makeSeries("C", "nivel_logro_C");

            chart3.appear(1000, 100); // Animar el gráfico al actualizar
        }
    </script>

@endsection
