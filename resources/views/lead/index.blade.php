@extends('ZLeader::layouts.master')

@section('title')
    Leads - @parent
@stop

@section('page_header', 'Leads')

@section('styles')
    <link rel="stylesheet" href="{{ URL::asset('vendor/ZLeader/assets/css/datepicker.css') }}">
@stop

@section('scripts')
    <script src="{{ URL::asset('vendor/ZLeader/assets/js/moment.js') }}"></script>
    <script src="{{ URL::asset('vendor/ZLeader/assets/js/bootstrap-datetimepicker.js') }}"></script>

    <script>
        $(function () {
            // Date Picker
            $('.datePicker').datetimepicker({
                pickTime: false
            });

            var xhr_lead;
            var xhr_forms;

            $('.table tbody tr').on('click', function (event) {
                event.preventDefault();

                $("#loadMe").modal({
                    backdrop: "static",
                    keyboard: false,
                    show: true
                });

                try {
                    xhr_lead.abort();
                    xhr_forms.abort();
                } catch (e) {}

                xhr_lead = $.ajax({
                    dataType: "json",
                    url: "leads/" + $(this).data('lead-id')
                })
                    .done(function (lead) {
                        $('#leadShow .modal-body').empty();
                        addLeadField('Id', lead.id);
                        $.each(lead.values, function (index, value) {
                            addLeadField(value.label, value.value);
                            $('#leadShow').modal('show');
                        });
                        addLeadField('Fecha', lead.created_at);
                        addLeadField('Empresa', lead.company_name);
                        addLeadField('Área', lead.area_name);
                        addLeadField('Formulario', lead.form_name);
                        addLeadField('Formulario', '<select id="lead-form-list"><option>Cargando...</option></select>');
                        addLeadField('Dispositivo', lead.remote_platform);
                        addLeadField('UTM Source', lead.utm_source);
                        addLeadField('UTM Campaign', lead.utm_campaign);
                        addLeadField('UTM medium', lead.utm_medium);
                        addLeadField('UTM Term', lead.utm_term);
                        addLeadField('UTM Content', lead.utm_content);
                        addLeadField('URL', lead.referer);
                        addLeadField('IP', lead.remote_ip);

                        xhr_forms = $.ajax({
                            dataType: "json",
                            url: "forms/json"
                        })
                            .done(function (forms) {
                                var $formSelect = $('#lead-form-list');
                                $formSelect.empty();
                                $.each(forms, function (index, value) {
                                    $formSelect.append('<option value="' + value.id + '">' + value.name + ' / ' + value.area.name + ' / ' + value.area.company.name + '</option>');
                                });
                                $formSelect.val(lead.id);
                                $formSelect.data('current', lead.id);

                                $formSelect.on('change', function (event) {
                                    if (confirm('Esta seguro de cambiar el lead de formulario?')) {
                                        var selected_form_id = $(this).val();

                                        $.ajax({
                                            method: "patch",
                                            dataType: "json",
                                            url: "leads/" + lead.id,
                                            data: {
                                                form_id: selected_form_id,
                                                notify: 1
                                            }
                                        })
                                            .done(function (forms) {
                                                $('#leadShow').modal('hide');
                                                location.reload();
                                            });
                                    } else {
                                        $(this).val($.data(this, 'current'));
                                        return false;
                                    }

                                    $.data(this, 'current', $(this).val());
                                });

                                $("#loadMe").modal("hide");
                            });
                    });

                return false;
            });

            function addLeadField(name, value) {
                if (value != '') {
                    $('#leadShow .modal-body').append('<div class="row"><div class="col-sm-4 text-right"><strong>' + name + ':</strong></div><div class="col-sm-8">' + value + '</div></div>');
                }
            }
        });
    </script>
@endsection

{{-- Page content --}}
@section('content')
    <div class="overlay">
        <div>
            <span></span>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Filtros</h3>
                </div>
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
                <div class="clearfix">
                    {{-- <div class="col-md-2">
                        <div class="btn-group">
                            <button name="export" type="button" class="btn btn-default dropdown-toggle"
                                    data-toggle="dropdown">
                                Exportar <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="#" data-grid="standard" data-download="csv">Exportar a CSV</a></li>
                            </ul>
                        </div>
                    </div> --}}
                    <form>
                        {{-- Date picker : Start date --}}
                        <div class="col-md-2">
                            <div class="form-group">
                                <div class="input-group datePicker" data-grid="standard" data-range-filter>
                                    <input type="text" data-format="DD MMM, YYYY" disabled class="form-control"
                                           data-range-start data-range-filter="created_at" data-label="Fecha"
                                           placeholder="Fecha desde">
                                    <span class="input-group-addon" style="cursor: pointer;"><i
                                                class="fa fa-calendar"></i></span>
                                </div>
                            </div>
                        </div>

                        {{-- Date picker : End date --}}
                        <div class="col-md-2">
                            <div class="form-group">
                                <div class="input-group datePicker" data-grid="standard" data-range-filter>
                                    <input type="text" data-format="DD MMM, YYYY" disabled class="form-control"
                                           data-range-end data-range-filter="created_at" data-label="Fecha"
                                           placeholder="Fecha hasta">
                                    <span class="input-group-addon" style="cursor: pointer;"><i
                                                class="fa fa-calendar"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 form-inline">
                            <div class="form-group">
                                <select name="addFilterColumn" class="form-control">
                                    <option value="">-- Seleccione campo --</option>
                                    @foreach($filtrables as $field)
                                        <option value="{{ $field->key }}">{{ $field->name }}</option>
                                    @endforeach
                                    <option value="form_name">Formulario</option>
                                    <option value="area_name">Area</option>
                                    <option value="company_name">Empresa</option>
                                    <option value="utm_source">UTM Source</option>
                                    <option value="utm_medium">UTM Medium</option>
                                    <option value="utm_campaign">UTM Campaign</option>
                                    <option value="utm_term">UTM Term</option>
                                    <option value="utm_content">UTM Content</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <input type="text" name="addFilterValue" placeholder="Buscar" class="form-control">
                            </div>
                            <button type="submit" class="btn btn-default">Buscar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Applied filters --}}
    @if(!empty(Session::get('zlLeadFilters')))
        <div class="row">
            <div class="col-lg-12">
                <div class="box">
                    <div class="box-body">
                        <div class="applied-filters" data-grid="standard">
                            @foreach(Session::get('zlLeadFilters') as $column => $filter)
                                <span class="label label-default"><strong>{{ $filter['name'] }}:</strong> {{ $filter['value'] }} <a
                                            class="text-red"
                                            href="{{ action('\Zephia\ZLeader\Http\Controllers\LeadController@index', ['clearFilter' => $column]) }}"><i
                                                class="fa fa-close"></i></a></span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    {{-- Results --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Lista</h3>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover table-leads"
                           data-source="{{ action('\Zephia\ZLeader\Http\Controllers\LeadController@datagrid') }}"
                           data-grid="standard">
                        <thead>
                        <tr>
                            <th class="sortable" data-grid="standard" data-sort="date">Fecha</th>
                            @foreach($columnables as $field)
                                <th class="sortable" data-grid="standard"
                                    data-sort="{{ $field->key }}">{{ $field->name }}</th>
                            @endforeach
                            <th class="sortable" data-grid="standard" data-sort="utm_source">Source</th>
                            <th class="sortable" data-grid="standard" data-sort="utm_medium">Medium</th>
                            <th class="sortable" data-grid="standard" data-sort="utm_medium">Empresa</th>
                            <th class="sortable" data-grid="standard" data-sort="utm_medium">Área</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($leads as $lead)
                            <tr data-lead-id="{{ $lead->id }}">
                                <td>{{ $lead->created_at->format('d/m/Y H:i') }}</td>
                                @foreach($columnables as $field)
                                    <td>{{ @$lead->values->where('key', $field->key)->first()->value }}</td>
                                @endforeach
                                <td>{{ $lead->utm_source }}</td>
                                <td>{{ $lead->utm_medium }}</td>
                                <td>{{ $lead->form->area->company->name }}</td>
                                <td>{{ $lead->form->area->name }}</td>
                            </tr>
                        @endforeach
                        @if(count($leads) == 0)
                            <tr>
                                <td colspan="20" align="center">No se encontraron registros</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Pagination --}}
    <footer id="pagination" data-grid="standard" class="text-right">{{ $leads->links() }}</footer>

    <!-- Modal -->
    <div class="modal fade" id="leadShow" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Detalle del lead</h4>
                </div>
                <div class="modal-body">
                    <div class="row">


                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- loader Modal -->
    <div class="modal fade" id="loadMe" tabindex="-1" role="dialog" aria-labelledby="loadMeLabel">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <div class="zleader-loader-spinner"></div>
                    <div clas="loader-txt">
                        <p>Cargando...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection