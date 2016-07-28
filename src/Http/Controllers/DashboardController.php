<?php

namespace Zephia\ZLeader\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Zephia\ZLeader\Model\Company;
use Zephia\ZLeader\Model\Lead;
use Illuminate\Http\Request;
 
class DashboardController extends Controller
{
    public function index(Request $request)
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

        $hex_colors = [
            [
                'color'     => '#f56954',
                'highlight' => '#f56954',
            ],
            [
                'color'     => '#00a65a',
                'highlight' => '#00a65a',
            ],
            [
                'color'     => '#f39c12',
                'highlight' => '#f39c12',
            ],
            [
                'color'     => '#00c0ef',
                'highlight' => '#00c0ef',
            ],
            [
                'color'     => '#3c8dbc',
                'highlight' => '#3c8dbc',
            ],
            [
                'color'     => '#d2d6de',
                'highlight' => '#d2d6de',
            ],
        ];

        $area_data = [];

        if ($request->has('company_id')) {
            if ($request->company_id) {
                $area_data = [];
                $company = Company::find($request->company_id);

                $months = DB::table('zleader_leads')
                    ->select(
                        'zleader_leads.created_at',
                        DB::raw('MONTH(zleader_leads.created_at) as month'),
                        DB::raw('YEAR(zleader_leads.created_at) as year')
                    )
                    ->groupBy('year','month')
                    ->get();

                foreach ($company->areas as $area) {
                    $months_formated = [];
                    foreach($months as $month) {
                        $months_data = DB::table('zleader_leads')
                            ->leftJoin('zleader_forms', 'zleader_leads.form_id', '=', 'zleader_forms.id')
                            ->select(
                                DB::raw('COUNT(*) as total')
                            )
                            ->where('zleader_forms.area_id','=',$area->id)
                            ->where(DB::raw("MONTH(zleader_leads.created_at)"), "=", DB::raw("MONTH(STR_TO_DATE('" . $month->month . "', '%m'))"))
                            ->where(DB::raw("YEAR(zleader_leads.created_at)"), "=", DB::raw("YEAR(STR_TO_DATE('" . $month->year . "', '%Y'))"))
                            ->first();
                        $formated_month = Carbon::parse($month->created_at)->formatLocalized('%B');
                        $months_formated[] = [
                            'month' => $formated_month,
                            'total' => $months_data->total,
                        ];
                    }

                    $area_data[] = [
                        'area_name' => $area->name,
                        'months'    => $months_formated,
                    ];
                }
            }
        }

        // Companies count
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

        // Leads por medium
        $leads_medium = DB::table('zleader_leads')
            ->select(
                'utm_medium as name', 
                DB::raw('count(*) as total')
            )
            ->where('utm_medium','!=','')
            ->groupBy('utm_medium')
            ->get();

        // Leads por source
        $leads_source = DB::table('zleader_leads')
            ->select(
                'utm_source as name', 
                DB::raw('count(*) as total'),
                DB::raw('@curRow := @curRow + 1 AS row_number')
            )
            ->where('utm_source','!=','')
            ->groupBy('utm_source')
            ->get();

        // bar chart

        $companies_data = Company::all();

        $bar_chart_data = [];

        $months = DB::table('zleader_leads')
            ->select(
                'zleader_leads.created_at',
                DB::raw('MONTH(zleader_leads.created_at) as month'),
                DB::raw('YEAR(zleader_leads.created_at) as year')
            )
            ->groupBy('year','month')
            ->get();

        //dd($months);

        foreach($companies_data as $company) {

            $months_formated = [];
            foreach($months as $month) {
                $months_data = DB::table('zleader_leads')
                    ->leftJoin('zleader_forms', 'zleader_leads.form_id', '=', 'zleader_forms.id')
                    ->leftJoin('zleader_areas', 'zleader_forms.area_id', '=', 'zleader_areas.id')
                    ->select(
                        DB::raw('COUNT(*) as total')
                    )
                    ->where('zleader_areas.company_id','=',$company->id)
                    ->where(DB::raw("MONTH(zleader_leads.created_at)"), "=", DB::raw("MONTH(STR_TO_DATE('" . $month->month . "', '%m'))"))
                    ->where(DB::raw("YEAR(zleader_leads.created_at)"), "=", DB::raw("YEAR(STR_TO_DATE('" . $month->year . "', '%Y'))"))
                    ->first();
                $formated_month = Carbon::parse($month->created_at)->formatLocalized('%B');
                $months_formated[] = [
                    'month' => $formated_month,
                    'total' => $months_data->total,
                ];
            }

            $bar_chart_data[] = [
                'company_name' => $company->name,
                'months' => $months_formated,
            ];
        }

        //dd($bar_chart_data);

        // Platforms
        $leads = Lead::all();

        $platforms_count = [
            'Desktop' => 0,
            'Mobile' => 0,
            'Tablet' => 0,
        ];

        foreach($leads as $lead) {
            if(!empty($lead->remote_platform)) {
                $platforms_count[$lead->remote_platform]++;
            }
        }

        //dd($platforms_count);

        return view('ZLeader::dashboard', [
            'colors'          => $colors,
            'hex_colors'      => $hex_colors,
            'companies_count' => $companies_count,
            'leads_medium'    => $leads_medium,
            'leads_source'    => $leads_source,
            'area_data'       => $area_data,
            'bar_chart_data'  => $bar_chart_data,
            'platforms_count' => $platforms_count,
        ]);
    }
}
