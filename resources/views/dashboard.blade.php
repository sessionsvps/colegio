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
        am5.ready(function() {
            //var userId = "{{ Auth::user()->id }}"; // Obtener el ID del usuario autenticado

            // Create root element for first chart
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

            // Create root element for second chart
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
@endsection
