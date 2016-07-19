<?php

namespace Zephia\ZLeader\Http\Controllers;

use Illuminate\Routing\Controller;
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

    public function datagrid()
    {
        $leads = Lead::all();

        $data = [];

        foreach ($leads as $lead) {
            $row = [];
            $row['id'] = $lead->id;
            $row['date'] = $lead->created_at->format('d/m/Y H:i:s');
            $row['created_at'] = $lead->created_at;
            $row['form_name'] = $lead->form->name;
            $row['utm_source'] = $lead->utm_source;
            $row['utm_medium'] = $lead->utm_medium;
            $row['utm_campaign'] = $lead->utm_campaign;
            $row['utm_content'] = $lead->utm_content;
            $row['utm_term'] = $lead->utm_term;
            foreach ($lead->values as $lead_value) {
                $row[$lead_value->key] = $lead_value->value;
            }
            
            $data[] = $row;
        }

        //dd($data);

        return DataGrid::make($data, array_keys($data[0]), ['sort' => 'date', 'direction' => 'desc']);
    }
}
