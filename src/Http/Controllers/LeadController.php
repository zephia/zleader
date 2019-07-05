<?php

namespace Zephia\ZLeader\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use DataGrid;
use Illuminate\Support\Facades\Session;
use Zephia\ZLeader\Model\Lead;
use Zephia\ZLeader\Model\Field;
use DB;
 
class LeadController extends Controller
{
    public function index()
    {
        DB::enableQueryLog();

        $columnables = Field::columnables()->get();
        $filtrables = Field::filtrables()->get();

        $leadQuery = Lead::with([
                'form.area.company',
                'values'
            ])
            ->orderBy('id', 'desc');

        $fixedColumns = [
            'form_name' => 'Formulario',
            'area_name' => 'Area',
            'company_name' => 'Empresa',
            'utm_source' => 'UTM Source',
            'utm_medium' => 'UTM Medium',
            'utm_campaign' => 'UTM Campaign',
            'utm_term' => 'UTM Term',
            'utm_content' => 'UTM Content',
        ];

        $currentFilters = Session::get('zlLeadFilters');
        if (Input::has('addFilterColumn') && Input::has('addFilterValue')) {
            if (array_key_exists(Input::get('addFilterColumn'), $fixedColumns)) {
                $currentFilters[Input::get('addFilterColumn')] = [
                    'name' => $fixedColumns[Input::get('addFilterColumn')],
                    'value' => Input::get('addFilterValue'),
                ];
            } else {
                $currentFilters[Input::get('addFilterColumn')] = [
                    'name' => $filtrables->where('key', Input::get('addFilterColumn'))->first()->name,
                    'value' => Input::get('addFilterValue'),
                ];
            }
        }

        if (Input::has('clearFilter')) {
            unset($currentFilters[Input::get('clearFilter')]);
        }

        Session::put('zlLeadFilters', $currentFilters);
        
        //dd(Session::all());

        if (!empty($currentFilters)) {
            foreach ($currentFilters as $appliedFilterKey => $appliedFilter) {
                if (array_key_exists($appliedFilterKey, $fixedColumns)) {
                    switch ($appliedFilterKey) {
                        case 'form_name';
                            $leadQuery->whereHas('form', function ($query) use ($appliedFilterKey, $appliedFilter) {
                                $query->where('name', 'like', '%' . $appliedFilter['value'] . '%');
                            });
                            break;
                        case 'area_name';
                            $leadQuery->whereHas('form', function ($query) use ($appliedFilterKey, $appliedFilter) {
                                $query->whereHas('area', function ($query) use ($appliedFilterKey, $appliedFilter) {
                                    $query->where('name', 'like', '%' . $appliedFilter['value'] . '%');
                                });
                            });
                            break;
                        case 'company_name';
                            $leadQuery->whereHas('form', function ($query) use ($appliedFilterKey, $appliedFilter) {
                                $query->whereHas('area', function ($query) use ($appliedFilterKey, $appliedFilter) {
                                    $query->whereHas('company', function ($query) use ($appliedFilterKey, $appliedFilter) {
                                        $query->where('name', 'like', '%' . $appliedFilter['value'] . '%');
                                    });
                                });
                            });
                            break;
                        default:
                            $leadQuery->where($appliedFilterKey, 'like', '%' . $appliedFilter['value'] . '%');
                    }
                } else {
                    $leadQuery->whereHas('values', function ($query) use ($appliedFilterKey, $appliedFilter) {
                        $query->where('key', $appliedFilterKey)
                            ->where('value', 'like', '%' . $appliedFilter['value'] . '%');
                    });
                }
            }
        }

        $leads = $leadQuery->paginate(50);

        return view('ZLeader::lead.index', [
            'leads' => $leads,
            'columnables' => $columnables,
            'filtrables' => $filtrables,
        ]);
    }

    public function show(Request $request, $lead_id)
    {
        $lead = Lead::with(['values', 'values.field'])->find($lead_id);

        $response_data = $lead;

        $response_data['form_name'] = $lead->form->name;
        $response_data['area_name'] = $lead->form->area->name;
        $response_data['company_name'] = $lead->form->area->company->name;

        unset(
            $response_data['updated_at'], 
            $response_data['notify'],
            $response_data['fb_leadgen_id'], 
            $response_data['user_agent'],
            $response_data['form']
        );

        return Response::json($response_data);
    }

    public function datagrid()
    {
        DB::enableQueryLog();

        $leads = Lead::with([
                'form.area.company',
                'values'
            ])
            ->orderBy('id', 'desc')
            ->get();

        $data = [];
        $keys = [];

        foreach ($leads as $lead) {
            $row = [];
            $row['id'] = $lead->id;
            $row['date'] = $lead->created_at->format('d/m/Y H:i:s');
            $row['created_at'] = $lead->created_at;
            $row['company_name'] = $lead->form->area->company->name;
            $row['area_name'] = $lead->form->area->name;
            $row['form_name'] = $lead->form->name;
            $row['utm_source'] = $lead->utm_source;
            $row['utm_medium'] = $lead->utm_medium;
            $row['utm_campaign'] = $lead->utm_campaign;
            $row['utm_content'] = $lead->utm_content;
            $row['utm_term'] = $lead->utm_term;
            $row['referer'] = $lead->referer;

            $keys[] = 'id';
            $keys[] = 'date';
            $keys[] = 'created_at';
            $keys[] = 'company_name';
            $keys[] = 'area_name';
            $keys[] = 'form_name';
            $keys[] = 'utm_source';
            $keys[] = 'utm_medium';
            $keys[] = 'utm_campaign';
            $keys[] = 'utm_content';
            $keys[] = 'utm_term';
            $keys[] = 'referer';

            // inject values
            foreach ($lead->values as $lead_value) {
                $row[$lead_value->key] = $lead_value->value;

                if(!in_array($lead_value->key, $keys)) {
                    $keys[] = $lead_value->key;
                }
            }
            
            $data[] = $row;
        }

        dd(DB::getQueryLog(), $data);

        return DataGrid::make($data, $keys, [
            'sort' => 'id',
            'direction' => 'desc',
            'csv_delimiter' => ';',
        ]);
    }

    public function patch($lead_id)
    {
        $lead = Lead::findOrFail($lead_id);

        if(!empty(Input::get('form_id'))){
            $lead->form_id = (int)Input::get('form_id');
        }

        if(!empty(Input::get('notify'))) {
            $lead->notify = (int)Input::get('notify');
        }

        if(!empty(Input::get('notes'))) {
            $lead->notes = Input::get('notes');
        }

        $lead->save();

        return $lead;
    }
}
