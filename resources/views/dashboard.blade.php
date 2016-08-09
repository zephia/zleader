@extends('ZLeader::layouts.master')

@section('page_header', 'Dashboard')

@section('styles')
<link rel="stylesheet" href="{{ URL::asset('vendor/ZLeader/almasaeed2010/adminlte/plugins/daterangepicker/daterangepicker.css') }}">
@stop
@section('scripts')
<!-- jQuery 2.2.3 -->
<script src="{{ URL::asset('vendor/ZLeader/almasaeed2010/adminlte/plugins/jQuery/jquery-2.2.3.min.js') }}"></script>
<!-- Bootstrap 3.3.6 -->
<script src="{{ URL::asset('vendor/ZLeader/almasaeed2010/adminlte/bootstrap/js/bootstrap.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ URL::asset('vendor/ZLeader/almasaeed2010/adminlte/dist/js/app.min.js') }}"></script>
<script src="{{ URL::asset('vendor/ZLeader/almasaeed2010/adminlte/plugins/chartjs/Chart.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="{{ URL::asset('vendor/ZLeader/almasaeed2010/adminlte/plugins/daterangepicker/daterangepicker.js') }}"></script>
<script type="text/javascript">
$(function() {

        'use strict';

        /* Date range */
        $('#daterange-btn').daterangepicker(
            {
                autoApply: true,
                autoUpdateInput: false,
                ranges: {
                    'Hoy': [moment(), moment()],
                    'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Últimos 7 días': [moment().subtract(6, 'days'), moment()],
                    'Últimos 30 días': [moment().subtract(29, 'days'), moment()],
                    'Este mes': [moment().startOf('month'), moment().endOf('month')],
                    'Último mes': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                    'Últimos 6 meses': [moment().subtract(6, 'month').startOf('month'), moment().endOf('month')],
                    'Siempre': [moment().subtract(96, 'month').startOf('month'), moment()]
                },
                startDate: moment().subtract(96, 'month').startOf('month'),
                endDate: moment()
            },
            function (start, end) {
                $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                $('#df').val(start);
                $('#dt').val(end);
                $('#form-filters').submit();
            }
        );

        $('#company_id').on('change', function(){
            $('#form-filters').submit();
        });



        /* ChartJS
         * -------
         * Here we will create a few charts using ChartJS
         */

        //-----------------------
        //- MONTHLY SALES CHART -
        //-----------------------
        @if(count($area_data) > 0)
            // Get context with jQuery - using jQuery's .get() method.
            var salesChartCanvas = $("#salesChart").get(0).getContext("2d");
            // This will get the first returned node in the jQuery collection.
            var salesChart = new Chart(salesChartCanvas);


            var salesChartData = {
                labels: [
                    @foreach($area_data[0]['months'] as $data_item)
                        "{{ $data_item['month'] }}",
                    @endforeach
                ],
                datasets: [
                    @foreach($area_data as $data_item)
                        {
                            label: "{{ $data_item['area_name'] }}",
                            fillColor: "rgba(210, 214, 222, 1)",
                            strokeColor: "rgba(210, 214, 222, 1)",
                            pointColor: "rgba(210, 214, 222, 1)",
                            pointStrokeColor: "#c1c7d1",
                            pointHighlightFill: "#fff",
                            pointHighlightStroke: "rgba(220,220,220,1)",
                            data: [
                                @foreach($data_item['months'] as $month_item)
                                    {{ $month_item['total'] }},
                                @endforeach
                            ]
                        },
                    @endforeach
                ]
            };

            var salesChartOptions = {
                //Boolean - If we should show the scale at all
                showScale: true,
                //Boolean - Whether grid lines are shown across the chart
                scaleShowGridLines: false,
                //String - Colour of the grid lines
                scaleGridLineColor: "rgba(0,0,0,.05)",
                //Number - Width of the grid lines
                scaleGridLineWidth: 1,
                //Boolean - Whether to show horizontal lines (except X axis)
                scaleShowHorizontalLines: true,
                //Boolean - Whether to show vertical lines (except Y axis)
                scaleShowVerticalLines: true,
                //Boolean - Whether the line is curved between points
                bezierCurve: true,
                //Number - Tension of the bezier curve between points
                bezierCurveTension: 0.3,
                //Boolean - Whether to show a dot for each point
                pointDot: false,
                //Number - Radius of each point dot in pixels
                pointDotRadius: 4,
                //Number - Pixel width of point dot stroke
                pointDotStrokeWidth: 1,
                //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
                pointHitDetectionRadius: 20,
                //Boolean - Whether to show a stroke for datasets
                datasetStroke: true,
                //Number - Pixel width of dataset stroke
                datasetStrokeWidth: 2,
                //Boolean - Whether to fill the dataset with a color
                datasetFill: true,
                //String - A legend template
                legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%=datasets[i].label%></li><%}%></ul>",
                //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
                maintainAspectRatio: true,
                //Boolean - whether to make the chart responsive to window resizing
                responsive: true
            };

            //Create the line chart
            salesChart.Line(salesChartData, salesChartOptions);
        @endif

        //---------------------------
        //- END MONTHLY SALES CHART -
        //---------------------------

        //-------------
        //- PIE CHART -
        //-------------
        // Get context with jQuery - using jQuery's .get() method.
        var pieChartCanvas = $("#pieChart").get(0).getContext("2d");
        var pieChart = new Chart(pieChartCanvas);
        var PieData = [
        @foreach($leads_medium as $data)
            {
                value: {{ $data->total }},
                //color: "#3c8dbc",
                //highlight: "#3c8dbc",
                label: '{{ !empty($data->name) ? $data->name : 'N/A' }}'
            },
        @endforeach
        ];
        var pieOptions = {
            //Boolean - Whether we should show a stroke on each segment
            segmentShowStroke: true,
            //String - The colour of each segment stroke
            segmentStrokeColor: "#fff",
            //Number - The width of each segment stroke
            segmentStrokeWidth: 1,
            //Number - The percentage of the chart that we cut out of the middle
            percentageInnerCutout: 50, // This is 0 for Pie charts
            //Number - Amount of animation steps
            animationSteps: 100,
            //String - Animation easing effect
            animationEasing: "easeOutBounce",
            //Boolean - Whether we animate the rotation of the Doughnut
            animateRotate: true,
            //Boolean - Whether we animate scaling the Doughnut from the centre
            animateScale: false,
            //Boolean - whether to make the chart responsive to window resizing
            responsive: true,
            // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
            maintainAspectRatio: false,
            //String - A legend template
            legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>",
            //String - A tooltip template
            tooltipTemplate: "<%=value %> <%=label%> users"
        };
        //Create pie or douhnut chart
        // You can switch between pie and douhnut using the method below.
        pieChart.Doughnut(PieData, pieOptions);
        //-----------------
        //- END PIE CHART -
        //-----------------

        //-------------
        //- PIE CHART -
        //-------------
        // Get context with jQuery - using jQuery's .get() method.
        var pieChartCanvas = $("#pieChart2").get(0).getContext("2d");
        var pieChart = new Chart(pieChartCanvas);
        var PieData = [
        @foreach($leads_source as $data)
            {
                value: {{ $data->total }},
                label: '{{ $data->name }}'
            },
        @endforeach
        ];
        var pieOptions = {
            //Boolean - Whether we should show a stroke on each segment
            segmentShowStroke: true,
            //String - The colour of each segment stroke
            segmentStrokeColor: "#fff",
            //Number - The width of each segment stroke
            segmentStrokeWidth: 1,
            //Number - The percentage of the chart that we cut out of the middle
            percentageInnerCutout: 50, // This is 0 for Pie charts
            //Number - Amount of animation steps
            animationSteps: 100,
            //String - Animation easing effect
            animationEasing: "easeOutBounce",
            //Boolean - Whether we animate the rotation of the Doughnut
            animateRotate: true,
            //Boolean - Whether we animate scaling the Doughnut from the centre
            animateScale: false,
            //Boolean - whether to make the chart responsive to window resizing
            responsive: true,
            // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
            maintainAspectRatio: false,
            //String - A legend template
            legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>",
            //String - A tooltip template
            tooltipTemplate: "<%=value %> <%=label%> users"
        };
        //Create pie or douhnut chart
        // You can switch between pie and douhnut using the method below.
        pieChart.Doughnut(PieData, pieOptions);
        //-----------------
        //- END PIE CHART -
        //-----------------

        //-------------
        //- BAR CHART -
        //-------------
        var areaChartData = {
            labels: [
            @foreach($bar_chart_data[0]['months'] as $data_item)
                "{{ $data_item['month'] }}",
            @endforeach
            ],
            datasets: [
            @foreach($bar_chart_data as $data_item)
                {
                    label: "{{ $data_item['company_name'] }}",
                    fillColor: "rgba(210, 214, 222, 1)",
                    strokeColor: "rgba(210, 214, 222, 1)",
                    pointColor: "rgba(210, 214, 222, 1)",
                    pointStrokeColor: "#c1c7d1",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(220,220,220,1)",
                    data: [
                    @foreach($data_item['months'] as $month_item)
                        {{ $month_item['total'] }},
                    @endforeach
                    ]
                },
            @endforeach
            ]
        };
        var barChartCanvas = $("#barChart").get(0).getContext("2d");
        var barChart = new Chart(barChartCanvas);
        var barChartData = areaChartData;
        var barChartOptions = {
            //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
            scaleBeginAtZero: true,
            //Boolean - Whether grid lines are shown across the chart
            scaleShowGridLines: true,
            //String - Colour of the grid lines
            scaleGridLineColor: "rgba(0,0,0,.05)",
            //Number - Width of the grid lines
            scaleGridLineWidth: 1,
            //Boolean - Whether to show horizontal lines (except X axis)
            scaleShowHorizontalLines: true,
            //Boolean - Whether to show vertical lines (except Y axis)
            scaleShowVerticalLines: true,
            //Boolean - If there is a stroke on each bar
            barShowStroke: true,
            //Number - Pixel width of the bar stroke
            barStrokeWidth: 2,
            //Number - Spacing between each of the X value sets
            barValueSpacing: 5,
            //Number - Spacing between data sets within X values
            barDatasetSpacing: 1,
            //String - A legend template
            legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].fillColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
            //Boolean - whether to make the chart responsive
            responsive: true,
            maintainAspectRatio: true
        };

        barChartOptions.datasetFill = false;
        barChart.Bar(barChartData, barChartOptions);
    });
</script>
@stop

@section('content')
<div class="box">
    <div class="box-header with-border">
        <div class="col-md-8">
            <h3 class="box-title">Filtros</h3>
        </div>
        <div class="col-md-4 text-right">
            @if(!empty($date_from) && !empty($date_to))
                <h3 class="box-title">Desde <strong>{{ $date_from->format('d/m/Y') }}</strong> hasta <strong>{{ $date_to->format('d/m/Y') }}</strong></h3>
            @endif
        </div>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-md-8">
                <form class="form-inline" method="get" id="form-filters">
                    @if(app('user') !== false)
                        @if(app('user')->inRole(app('admins_role')))
                        <div class="form-group">
                            <label>Filtrar por empresa:</label>
                            <select class="form-control" name="company_id" id="company_id">
                                <option value="">-- seleccione empresa --</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}" {{ ($company->id == Request::get('company_id')) ? 'selected="selected"' : '' }}>{{ $company->name }} </option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                    @endif
                    <div class="form-group">
                        <input type="hidden" name="df" id="df" />
                        <input type="hidden" name="dt" id="dt" />
                        <div class="input-group">
                            <button type="button" class="btn btn-default pull-right" id="daterange-btn">
                                <span>
                                    <i class="fa fa-calendar"></i> Elegir rango de fechas
                                </span>
                                <i class="fa fa-caret-down"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="false">Reportes totales</a></li>
        <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="true">Reportes históricos</a></li>
    </ul>
    <div class="tab-content"> 
        <div class="tab-pane active" id="tab_1">-->
            <h4>Por empresa</h4>
            <div class="row">
                @foreach($companies_count as $company_count)
                <div class="col-md-4">
                    <div class="box box-widget widget-company-count widget-user-2">
                        <div class="widget-user-header bg-{{ $colors[$company_count->index] }}">
                            <div class="widget-user-image">
                                <img class="img-circle" src="{{ $company_count->image }}" alt="User Avatar">
                            </div>
                            <h3 class="widget-user-username">{{ $company_count->count }}</h3>
                            <h5 class="widget-user-desc">{{ $company_count->name }}</h5>
                        </div>
                        <div class="box-footer no-padding">
                            <ul class="nav nav-stacked">
                            @if(!empty($company_count->areas))
                                @foreach($company_count->areas as $area)
                                <li><a href="#">{{ $area->name }} <span class="pull-right badge bg-{{ $colors[$area->index] }}">{{ $area->count }}</span></a></li>
                                @endforeach
                            @endif
                            </ul>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <h4>Por dispositivo</h4>
            <div class="row">
                <div class="col-md-4">
                    <div class="small-box bg-green">
                        <div class="inner">
                            <h3>{{ round($platforms_data['Mobile']) }}%</h3>
                            <p>Mobile</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-mobile"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="small-box bg-aqua">
                        <div class="inner">
                            <h3>{{ round($platforms_data['Tablet']) }}%</h3>
                            <p>Tablet</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-tablet"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="small-box bg-yellow">
                        <div class="inner">
                            <h3>{{ round($platforms_data['Desktop']) }}%</h3>
                            <p>Desktop</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-desktop"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="box box-default">
                        <div class="box-header with-border">
                            <h3 class="box-title">Leads por medio</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="chart-responsive">
                                        <canvas id="pieChart" height="150"></canvas>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <ul class="chart-legend clearfix">
                                    @foreach($leads_medium as $data)
                                        <li><i class="fa fa-circle-o text-red"></i> {{ !empty($data->name) ? $data->name : 'N/A' }} ({{ $data->total }})</li>
                                    @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="box box-default">
                        <div class="box-header with-border">
                            <h3 class="box-title">Leads por fuente</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="chart-responsive">
                                        <canvas id="pieChart2" height="150"></canvas>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <ul class="chart-legend clearfix">
                                    @foreach($leads_source as $data)
                                        <li><i class="fa fa-circle-o text-red"></i> {{ $data->name }} ({{ $data->total }})</li>
                                    @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <!--/div>
        <div class="tab-pane" id="tab_2"-->
            <h4>Comparativa mensual</h4>
            <div class="chart">
                <canvas id="barChart" style="height:230px"></canvas>
            </div>
            @if(isset($company_id))
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Reporte histórico por área</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="chart">
                                <canvas id="salesChart" style="height: 180px;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        <!--</div>
    </div>
</div>-->
@stop