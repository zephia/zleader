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

        $company_id = null;

        if (app('user') !== false) {
            if (app('user')->inRole(app('users_role'))) {
                $company_id = app('user')->company_id;
            } elseif(app('user')->inRole(app('admins_role'))) {
                $companies = Company::all();
                if ($request->has('company_id')) {
                    $company_id = $request->company_id;
                }
            }
        }

        if ($request->has('df')) {
            $date_from = new Carbon($request->df);
        }
        if ($request->has('dt')) {
            $date_to = new Carbon($request->dt);
        }

        if (isset($company_id)) {
            $area_data = [];
            $company = Company::find($company_id);

            $months_db = DB::table('zleader_leads')
                ->select(
                    'zleader_leads.created_at',
                    DB::raw('MONTH(zleader_leads.created_at) as month'),
                    DB::raw('YEAR(zleader_leads.created_at) as year')
                )
                ->groupBy('year','month');

            if (isset($date_from) && isset($date_to)) {
                $months_db
                    ->whereDate('zleader_leads.created_at', '>=', $date_from)
                    ->whereDate('zleader_leads.created_at', '<=', $date_to);
            }

            $months = $months_db->get();

            foreach ($company->areas as $area) {
                $months_formated = [];
                foreach($months as $month) {
                    $months_db = DB::table('zleader_leads')
                        ->leftJoin('zleader_forms', 'zleader_leads.form_id', '=', 'zleader_forms.id')
                        ->select(
                            DB::raw('COUNT(*) as total')
                        )
                        ->where('zleader_forms.area_id','=',$area->id)
                        ->where(DB::raw("MONTH(zleader_leads.created_at)"), "=", DB::raw("MONTH(STR_TO_DATE('" . $month->month . "', '%m'))"))
                        ->where(DB::raw("YEAR(zleader_leads.created_at)"), "=", DB::raw("YEAR(STR_TO_DATE('" . $month->year . "', '%Y'))"));
                    if (isset($date_from) && isset($date_to)) {
                        $months_db
                            ->whereDate('zleader_leads.created_at', '>=', $date_from)
                            ->whereDate('zleader_leads.created_at', '<=', $date_to);
                    }
                    $months_data = $months_db->first();
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

        // Companies count
        if (isset($company_id)) {
            $companies_data = Company::where('id', '=', $company_id)->get();
        } else {
            $companies_data = Company::all();
        }
        $lead = new Lead;
        $companies_count = [];
        $company_index = 0;
        foreach($companies_data as $company) {
            $company_item = [];
            $company_item['name'] = $company->name;
            $company_item['image'] = $company->image;
            $count_data = DB::table('zleader_leads')
                ->select(DB::raw('count(*) as lead_count'))
                ->join('zleader_forms', 'zleader_forms.id', '=', 'zleader_leads.form_id')
                ->join('zleader_areas', 'zleader_areas.id', '=', 'zleader_forms.area_id')
                ->where('zleader_areas.company_id', '=', $company->id);
            if (isset($date_from) && isset($date_to)) {
                $count_data
                    ->whereDate('zleader_leads.created_at', '>=', $date_from)
                    ->whereDate('zleader_leads.created_at', '<=', $date_to);
            }
            $count_result = $count_data->get();
            $company_item['count'] = count($count_result) > 0 ? $count_result[0]->lead_count : 0;

            $areas_count = [];
            $area_index = 0;
            foreach($company->areas as $area) {
                $areas_item = [];
                $areas_item['name'] = $area->name;
                $count_data_area = DB::table('zleader_leads')
                    ->select(DB::raw('count(*) as lead_count'))
                    ->join('zleader_forms', 'zleader_forms.id', '=', 'zleader_leads.form_id')
                    ->where('zleader_forms.area_id', '=', $area->id);
                if (isset($date_from) && isset($date_to)) {
                    $count_data_area
                        ->whereDate('zleader_leads.created_at', '>=', $date_from)
                        ->whereDate('zleader_leads.created_at', '<=', $date_to);
                }
                $count_result_area = $count_data_area->get();
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
        $leads_data = DB::table('zleader_leads')
            ->select(
                'utm_medium as name', 
                DB::raw('count(*) as total')
            )
            ->groupBy('utm_medium');

        if (isset($company_id)) {
            $leads_data
                ->join('zleader_forms', 'zleader_forms.id', '=', 'zleader_leads.form_id')
                ->join('zleader_areas', 'zleader_areas.id', '=', 'zleader_forms.area_id')
                ->where('zleader_areas.company_id', '=', $company_id);
        }

        if (isset($date_from) && isset($date_to)) {
            $leads_data
                ->whereDate('zleader_leads.created_at', '>=', $date_from)
                ->whereDate('zleader_leads.created_at', '<=', $date_to);
        }

        $leads_medium = $leads_data->get();

        // Leads por source
        $leads_data = DB::table('zleader_leads')
            ->select(
                'utm_source as name', 
                DB::raw('count(*) as total'),
                DB::raw('@curRow := @curRow + 1 AS row_number')
            )
            ->where('utm_source','!=','')
            ->groupBy('utm_source');

        if (isset($date_from) && isset($date_to)) {
            $leads_data
                ->whereDate('zleader_leads.created_at', '>=', $date_from)
                ->whereDate('zleader_leads.created_at', '<=', $date_to);
        }

        if (isset($company_id)) {
            $leads_data
                ->join('zleader_forms', 'zleader_forms.id', '=', 'zleader_leads.form_id')
                ->join('zleader_areas', 'zleader_areas.id', '=', 'zleader_forms.area_id')
                ->where('zleader_areas.company_id', '=', $company_id);
        }

        $leads_source = $leads_data->get();

        // bar chart

        if (isset($company_id)) {
            $companies_data = Company::where('id', '=', $company_id)->get();
        } else {
            $companies_data = Company::all();
        }

        $bar_chart_data = [];

        $months_db = DB::table('zleader_leads')
            ->select(
                'zleader_leads.created_at',
                DB::raw('MONTH(zleader_leads.created_at) as month'),
                DB::raw('YEAR(zleader_leads.created_at) as year')
            )
            ->groupBy('year','month');

        if (isset($date_from) && isset($date_to)) {
            $months_db
                ->whereDate('zleader_leads.created_at', '>=', $date_from)
                ->whereDate('zleader_leads.created_at', '<=', $date_to);
        }

        $months = $months_db->get();

        //dd($months);

        foreach($companies_data as $company) {

            $months_formated = [];
            foreach($months as $month) {
                $months_db = DB::table('zleader_leads')
                    ->leftJoin('zleader_forms', 'zleader_leads.form_id', '=', 'zleader_forms.id')
                    ->leftJoin('zleader_areas', 'zleader_forms.area_id', '=', 'zleader_areas.id')
                    ->select(
                        DB::raw('COUNT(*) as total')
                    )
                    ->where('zleader_areas.company_id','=',$company->id)
                    ->where(DB::raw("MONTH(zleader_leads.created_at)"), "=", DB::raw("MONTH(STR_TO_DATE('" . $month->month . "', '%m'))"))
                    ->where(DB::raw("YEAR(zleader_leads.created_at)"), "=", DB::raw("YEAR(STR_TO_DATE('" . $month->year . "', '%Y'))"));

                if (isset($date_from) && isset($date_to)) {
                    $months_db
                        ->whereDate('zleader_leads.created_at', '>=', $date_from)
                        ->whereDate('zleader_leads.created_at', '<=', $date_to);
                }

                $months_data = $months_db->first();
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
        $leads_model = Lead::query();

        if (isset($company_id)) {
            $leads_model->whereHas('form', function($query) use ($company_id) {
                return $query->whereHas('area', function($query) use ($company_id) {
                    return $query
                        ->where('company_id', '=', $company_id);
                });
            });
        }

        $leads = $leads_model->get();

        $platforms_count = [
            'Desktop' => 0,
            'Mobile'  => 0,
            'Tablet'  => 0,
        ];
        $platforms_data = [
            'Desktop' => 0,
            'Mobile'  => 0,
            'Tablet'  => 0,
        ];
        $platforms_total = 0;

        foreach ($leads as $lead) {
            if(!empty($lead->remote_platform)) {
                $platforms_total ++;
                $platforms_count[$lead->remote_platform]++;
            }
        }

        foreach ($platforms_count as $key => $value) {
            if ($value !== 0) {
                $platforms_data[$key] =  $value / $platforms_total * 100;
            }
        }

        //dd($platforms_count);

        return view('ZLeader::dashboard', [
            'companies'       => isset($companies) ? $companies : null,
            'companies_data'  => $companies_data,
            'colors'          => $colors,
            'hex_colors'      => $hex_colors,
            'companies_count' => $companies_count,
            'leads_medium'    => $leads_medium,
            'leads_source'    => $leads_source,
            'area_data'       => $area_data,
            'bar_chart_data'  => $bar_chart_data,
            'platforms_data'  => $platforms_data,
            'company_id'      => $company_id,
            'date_from'       => isset($date_from) ? $date_from : null,
            'date_to'         => isset($date_to) ? $date_to : null,
        ]);
    }
}
