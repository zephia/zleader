@extends('ZLeader::layouts.master')

@section('title')
Leads - @parent
@stop

@section('page_header', 'Leads')

@section('styles')
<link rel="stylesheet" href="{{ URL::asset('vendor/ZLeader/assets/css/datepicker.css') }}">
@stop

@section('scripts')
<script src="{{ URL::asset('vendor/ZLeader/cartalyst/data-grid/js/underscore.js') }}"></script>
<script src="{{ URL::asset('vendor/ZLeader/cartalyst/data-grid/js/data-grid.js') }}"></script>
<script src="{{ URL::asset('vendor/ZLeader/assets/js/moment.js') }}"></script>
<script src="{{ URL::asset('vendor/ZLeader/assets/js/bootstrap-datetimepicker.js') }}"></script>

<script>
$(function()
{
    // Setup DataGrid
    var grid = $.datagrid('standard', '.table', '#pagination', '.applied-filters',
    {
        throttle: 20,
        loader: '.loader',
        callback: function(obj)
        {
            // Select the correct value on the per page dropdown
            $('[data-per-page]').val(obj.opt.throttle);
            // Disable the export button if no results
            $('button[name="export"]').prop('disabled', (obj.pagination.filtered === 0) ? true : false);
        }
    });
    // Date Picker
    $('.datePicker').datetimepicker({
        pickTime: false
    });
    /**
     * DEMO ONLY EVENTS
     */
    $('[data-per-page]').on('change', function(){
        grid.setThrottle($(this).val());
        grid.refresh();
    });
});
</script>
@stop

{{-- Page content --}}
@section('content')

<div class="loader" data-grid="standard">
    <div>
        <span></span>
    </div>
</div>
<div class="row">

    {{-- Filters button --}}
    <!--div class="col-md-1">
        <div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                Filters <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu">
                <li><a href="#" data-grid="standard" data-filter="country:United States" data-label="country:Country:United States">United States</a></li>
                <li><a href="#" data-grid="standard" data-filter="country:Canada" data-label="country:Country:Canada">Canada</a></li>
                <li><a href="#" data-grid="standard" data-filter="population:>:10000" data-label="population:Population >:10000">Populations > 10000</a></li>
                <li><a href="#" data-grid="standard" data-filter="population:=:5000" data-label="population:Populations is:5000">Populations = 5000</a></li>
                <li><a href="#" data-grid="standard" data-filter="population:>:5000">Populations > 5000</a></li>
                <li><a href="#" data-grid="standard" data-filter="population:<:5000">Populations < 5000</a></li>
                <li><a href="#" data-grid="standard" data-filter="country:United States, subdivision:washington, population:<:5000" data-label="country:Country:United States, subdivision:Subdivision:Washington, population:Population:5000">Washington, United States < 5000</a></li>
            </ul>
        </div>
    </div-->

    {{-- Export button --}}
    <div class="col-md-1">
        <div class="btn-group">
            <button name="export" type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                Exportar <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu">
                <li><a href="#" data-grid="standard" data-download="csv">Exportar a CSV</a></li>
                <li><a href="#" data-grid="standard" data-download="json">Exportar a JSON</a></li>
                <li><a href="#" data-grid="standard" data-download="pdf">Exportar a PDF</a></li>
            </ul>
        </div>
    </div>

    {{-- Date picker : Start date --}}
    <div class="col-md-2">
        <div class="form-group">
            <div class="input-group datePicker" data-grid="standard" data-range-filter>
                <input type="text" data-format="DD MMM, YYYY" disabled class="form-control" data-range-start data-range-filter="created_at" data-label="Fecha" placeholder="Fecha desde">
                <span class="input-group-addon" style="cursor: pointer;"><i class="fa fa-calendar"></i></span>
            </div>
        </div>
    </div>

    {{-- Date picker : End date --}}
    <div class="col-md-2">
        <div class="form-group">
            <div class="input-group datePicker" data-grid="standard" data-range-filter>
                <input type="text" data-format="DD MMM, YYYY" disabled class="form-control" data-range-end data-range-filter="created_at" data-label="Fecha" placeholder="Fecha hasta">
                <span class="input-group-addon" style="cursor: pointer;"><i class="fa fa-calendar"></i></span>
            </div>
        </div>
    </div>

    {{-- Results per page --}}
    <!--div class="col-md-2">
        <div class="form-group">
            <select data-per-page class="form-control">
                <option>Per Page</option>
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="30">30</option>
                <option value="40">40</option>
                <option value="50">50</option>
                <option value="100">100</option>
                <option value="200">200</option>
            </select>
        </div>
    </div-->
    <div class="col-md-7">
        <form data-search data-grid="standard" class="form-inline" role="form">
            <div class="form-group">
                <select name="column" class="form-control">
                    <option value="all">Todos</option>
                    @foreach($filtrables as $field)
                    <option value="{{ $field->key }}">{{ $field->name }}</option>
                    @endforeach
                    <option value="form_name">Formulario</option>
                    <option value="utm_source">UTM Source</option>
                    <option value="utm_medium">UTM Medium</option>
                    <option value="utm_campaign">UTM Campaign</option>
                    <option value="utm_term">UTM Term</option>
                    <option value="utm_content">UTM Content</option>
                </select>
            </div>
            <div class="form-group">
                <input type="text" name="filter" placeholder="Buscar" class="form-control">
            </div>
            <button type="submit" class="btn btn-default">Buscar</button>
        </form>
    </div>
</div>

{{-- Applied filters --}}
<div class="row">
    <div class="applied-filters" data-grid="standard"></div>
</div>

{{-- Results --}}
<div class="row">
    <div class="col-lg-12">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" data-source="{{ action('\Zephia\ZLeader\Http\Controllers\LeadController@datagrid') }}" data-grid="standard">
                <thead>
                    <tr>
                        <th class="sortable" data-grid="standard" data-sort="date">Fecha</th>
                        @foreach($columnables as $field)
                        <th class="sortable" data-grid="standard" data-sort="{{ $field->key }}">{{ $field->name }}</th>
                        @endforeach
                        <th class="sortable" data-grid="standard" data-sort="utm_source">Source</th>
                        <th class="sortable" data-grid="standard" data-sort="utm_medium">Medium</th>
                        <th class="sortable" data-grid="standard" data-sort="utm_campaign">Campaign</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

{{-- Pagination --}}
<footer id="pagination" data-grid="standard"></footer>

@include('ZLeader::lead.results', ['columnables' => $columnables])
@include('ZLeader::lead.no_results')
@include('ZLeader::lead.pagination')
@include('ZLeader::lead.filters')
@include('ZLeader::lead.no_filters')

@stop