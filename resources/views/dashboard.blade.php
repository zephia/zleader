@extends('ZLeader::layouts.master')

@section('page_header', 'Dashboard')

@section('styles')

<link rel="stylesheet" href="{{ URL::asset('vendor/ZLeader/almasaeed2010/adminlte/plugins/daterangepicker/daterangepicker.css') }}">
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
    //Date range as a button
    $('#daterange-btn').daterangepicker(
        {
          ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
          },
          startDate: moment().subtract(29, 'days'),
          endDate: moment()
        },
        function (start, end) {
          $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }
    );
</script>
<script type="text/javascript">
$(function() {

        'use strict';

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
        //- PIE CHART -
        //-------------
        // Get context with jQuery - using jQuery's .get() method.
        var pieChartCanvas = $("#pieChart2").get(0).getContext("2d");
        var pieChart = new Chart(pieChartCanvas);
        var PieData = [
        @foreach($leads_medium as $data)
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
        barChartData.datasets[1].fillColor = "#00a65a";
        barChartData.datasets[1].strokeColor = "#00a65a";
        barChartData.datasets[1].pointColor = "#00a65a";
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
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="false">Reportes totales</a></li>
        <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="true">Reportes históricos</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
            <form class="form-inline">
                <div class="form-group">
                    <label>Filtrar por empresa:</label>
                    <select class="form-control">
                        <option>-- seleccione empresa --</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Date range button:</label>
                    <div class="input-group">
                        <button type="button" class="btn btn-default pull-right" id="daterange-btn">
                            <span>
                                <i class="fa fa-calendar"></i> Date range picker
                            </span>
                            <i class="fa fa-caret-down"></i>
                        </button>
                    </div>
                </div>
                <button type="submit" class="btn btn-default">Filtrar</button>
            </form>
            <h4>Por Empresa</h4>
            <div class="row">
                @foreach($companies_count as $company_count)
                <div class="col-md-4">
                    <!-- Widget: user widget style 1 -->
                    <div class="box box-widget widget-company-count widget-user-2">
                        <!-- Add the bg color to the header using any of the bg-* classes -->
                        <div class="widget-user-header bg-{{ $colors[$company_count->index] }}">
                            <div class="widget-user-image">
                                <img class="img-circle" src="{{ $company_count->image }}" alt="User Avatar">
                            </div>
                            <!-- /.widget-user-image -->
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
                    <!-- /.widget-user -->
                </div>
                @endforeach
            </div>
            <h4>Por dispositivo</h4>
            <div class="row">
                <div class="col-md-4">
                    <!-- small box -->
                    <div class="small-box bg-green">
                        <div class="inner">
                            <h3>{{ $platforms_count['Mobile'] }}</h3>
                            <p>Mobile</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-mobile"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <!-- small box -->
                    <div class="small-box bg-aqua">
                        <div class="inner">
                            <h3>{{ $platforms_count['Tablet'] }}</h3>
                            <p>Tablet</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-tablet"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <!-- small box -->
                    <div class="small-box bg-yellow">
                        <div class="inner">
                            <h3>{{ $platforms_count['Desktop'] }}</h3>
                            <p>Desktop</p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-desktop"></i>
                        </div>
                    </div>
                </div>
            </div>
            <h4>Por empresa</h4>
            <div class="chart">
                <canvas id="barChart" style="height:230px"></canvas>
            </div>
        </div>
        <div class="tab-pane" id="tab_2">
            The European languages are members of the same family. Their separate existence is a myth. For science, music, sport, etc, Europe uses the same vocabulary. The languages only differ in their grammar, their pronunciation and their most common words. Everyone realizes why a new common language would be desirable: one could refuse to pay expensive translators. To achieve this, it would be necessary to have uniform grammar, pronunciation and more common words. If several languages coalesce, the grammar of the resulting language is more simple and regular than that of the individual languages.
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Reporte histórico por área</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="chart">
                            <!-- Sales Chart Canvas -->
                            <canvas id="salesChart" style="height: 180px;"></canvas>
                        </div>
                        <!-- /.chart-responsive -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- ./box-body -->
        </div>
        <!-- /.box -->
    </div>
    <!-- /.col -->
    <div class="col-md-6">
        <!-- /.info-box -->
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Leads por medio</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="chart-responsive">
                            <canvas id="pieChart" height="150"></canvas>
                        </div>
                        <!-- ./chart-responsive -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-4">
                        <ul class="chart-legend clearfix">
                        @foreach($leads_medium as $data)
                            <li><i class="fa fa-circle-o text-red"></i> {{ $data->name }}</li>
                        @endforeach
                        </ul>
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
        </div>
        <!-- /.box -->
    </div>
    <div class="col-md-6">
        <!-- /.info-box -->
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title">Leads por fuente</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="chart-responsive">
                            <canvas id="pieChart2" height="150"></canvas>
                        </div>
                        <!-- ./chart-responsive -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-4">
                        <ul class="chart-legend clearfix">
                        @foreach($leads_source as $data)
                            <li><i class="fa fa-circle-o text-red"></i> {{ $data->name }}</li>
                        @endforeach
                        </ul>
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
        </div>
        <!-- /.box -->
    </div>
</div>
@stop