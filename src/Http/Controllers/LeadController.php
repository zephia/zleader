<?php

namespace Zephia\ZLeader\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use DataGrid;
use Zephia\ZLeader\Model\Lead;
use Zephia\ZLeader\Model\Field;
 
class LeadController extends Controller
{
    public function index()
    {
        $columnables = Field::columnables()->get();
        $filtrables = Field::filtrables()->get();
        return view('ZLeader::lead.index', ['columnables' => $columnables, 'filtrables' => $filtrables]);
    }

    public function show(Request $request, $lead_id)
    {
        $lead = Lead::with(['values'])->find($lead_id);

        unset(
            $lead['updated_at'], 
            $lead['notify'], 
            $lead['form_id'], 
            $lead['fb_leadgen_id'], 
            $lead['user_agent']
        );

        return Response::json($lead);
    }

    public function datagrid()
    {
        $leads = Lead::all();

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

            // inject values
            foreach ($lead->values as $lead_value) {
                $row[$lead_value->key] = $lead_value->value;

                if(!in_array($lead_value->key, $keys)) {
                    $keys[] = $lead_value->key;
                }
            }
            
            $data[] = $row;
        }

        //dd($data);

        return DataGrid::make($data, $keys, ['sort' => 'date', 'direction' => 'desc']);
    }
}
