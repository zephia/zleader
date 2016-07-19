<?php

namespace Zephia\ZLeader\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Zephia\ZLeader\Model\Company;
use Zephia\ZLeader\Model\Lead;
 
class DashboardController extends Controller
{
    public function index()
    {
        $colors = [
            'blue',
            'yellow',
            'red',
            'green',
            'aqua',
            'navy',
            'teal',
            'olive',
            'lime',
            'orange',
            'fuchsia',
            'purple',
            'maroon',
            'black',
            'gray',
        ];

        //Companies count
        $companies_data = Company::all();
        $lead = new Lead;
        $companies_count = [];
        $company_index = 0;
        foreach($companies_data as $company) {
            $company_item = [];
            $company_item['name'] = $company->name;
            $company_item['image'] = $company->image;
            $count_result = DB::select('
                SELECT 
                    COUNT(*) as lead_count 
                FROM 
                    zleader_leads l
                    JOIN zleader_forms f ON l.form_id = f.id 
                    JOIN zleader_areas a ON f.area_id = a.id
                WHERE 
                    a.company_id = ?
                ', [$company->id]);
            $company_item['count'] = count($count_result) > 0 ? $count_result[0]->lead_count : 0;

            $areas_count = [];
            $area_index = 0;
            foreach($company->areas as $area) {
                $areas_item = [];
                $areas_item['name'] = $area->name;
                $count_result_area = DB::select('
                    SELECT 
                        COUNT(*) as lead_count 
                    FROM 
                        zleader_leads l
                        JOIN zleader_forms f ON l.form_id = f.id 
                    WHERE 
                        f.area_id = ?
                    ', [$area->id]);
                $areas_item['count'] = count($count_result_area) > 0 ? $count_result_area[0]->lead_count : 0;

                $areas_item['index'] = $area_index;

                $areas_count[] = (object)$areas_item;

                $area_index++;
            }

            if(count($company->areas)>0) {
                $company_item['areas'] = $areas_count;
            }

            $company_item['index'] = $company_index;

            $companies_count[] = (object)$company_item;

            $company_index++;
        }

        return view('ZLeader::dashboard', [
            'colors'          => $colors,
            'companies_count' => $companies_count,
        ]);
    }
}
