{{-- <x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <x-welcome />
            </div>
        </div>
    </div>
</x-app-layout> --}}

@extends('layouts.main')

<head>
    <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
</head>

@section('contenido')
    <p>DASHBOARD</p>
    @if(Auth::user()->hasRole('Estudiante_Matriculado'))
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
    @elseif (Auth::user()->hasRole('Director') || Auth::user()->hasRole('Secretaria') || Auth::user()->hasRole('Admin'))

    @elseif (Auth::user()->hasRole('Docente'))

    @endif
@endsection

@section('scripts')
    <script>
        am5.ready(function() {

            // Create root element
            var root = am5.Root.new("chartdiv1");

            // Set themes
            root.setThemes([
                am5themes_Animated.new(root)
            ]);

            // Create chart
            var chart = root.container.children.push(am5xy.XYChart.new(root, {
                panX: false,
                panY: false,
                paddingLeft: 0,
                wheelX: "panX",
                wheelY: "zoomX",
                layout: root.verticalLayout
            }));

            // Add legend
            var legend = chart.children.push(
                am5.Legend.new(root, {
                    centerX: am5.p50,
                    x: am5.p50
                })
            );

            // Load data from API
            am5.net.load("http://127.0.0.1:8000/api/asistencias").then(function(result) {
                var response = am5.JSONParser.parse(result.response);
                var asistencias = response.asistencias;
                console.log(asistencias);  // Log para verificar la estructura de los datos

                // Create axes
                var xRenderer = am5xy.AxisRendererX.new(root, {
                    cellStartLocation: 0.1,
                    cellEndLocation: 0.9,
                    minorGridEnabled: true
                });

                var xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
                    categoryField: "bimestre",
                    renderer: xRenderer,
                    tooltip: am5.Tooltip.new(root, {})
                }));

                xRenderer.grid.template.setAll({
                    location: 1
                });

                xAxis.data.setAll(asistencias);

                var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
                    renderer: am5xy.AxisRendererY.new(root, {
                        strokeOpacity: 0.1
                    })
                }));

                // Function to create series
                function makeSeries(name, fieldName) {
                    var series = chart.series.push(am5xy.ColumnSeries.new(root, {
                        name: name,
                        xAxis: xAxis,
                        yAxis: yAxis,
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
                        return am5.Bullet.new(root, {
                            locationY: 0,
                            sprite: am5.Label.new(root, {
                                text: "{valueY}",
                                fill: root.interfaceColors.get("alternativeText"),
                                centerY: 0,
                                centerX: am5.p50,
                                populateText: true
                            })
                        });
                    });

                    legend.data.push(series);
                }

                // Create series for each category
                makeSeries("Inasistencias Justificadas", "inasistencias_justificadas");
                makeSeries("Inasistencias Injustificadas", "inasistencias_injustificadas");
                makeSeries("Tardanzas Justificadas", "tardanzas_justificadas");
                makeSeries("Tardanzas Injustificadas", "tardanzas_injustificadas");

                // Make stuff animate on load
                chart.appear(1000, 100);
            }).catch(function(result) {
                console.log("Error loading " + result.xhr.responseURL);
            });

        }); // end am5.ready()
    </script>

    <script>
        am5.ready(function() {

            // Create root element
            var root = am5.Root.new("chartdiv2");

            // Set themes
            root.setThemes([
                am5themes_Animated.new(root)
            ]);

            // Create chart
            var chart = root.container.children.push(
                am5percent.PieChart.new(root, {
                    endAngle: 270
                })
            );

            // Create series
            var series = chart.series.push(
                am5percent.PieSeries.new(root, {
                    valueField: "total",
                    categoryField: "nivel_logro",
                    endAngle: 270
                })
            );

            series.states.create("hidden", {
                endAngle: -90
            });

            // Load data from API
            am5.net.load("http://127.0.0.1:8000/api/asistencias").then(function(result) {
                var response = am5.JSONParser.parse(result.response);
                var logros = response.logros;

                // Set data
                series.data.setAll(logros);

                series.appear(1000, 100);
            }).catch(function(result) {
                console.log("Error loading " + result.xhr.responseURL);
            });

        }); // end am5.ready()
    </script>
@endsection
