<?php

namespace Zephia\ZLeader\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
 
class LeadController extends Controller
{
    public function store(Request $request, $form_slug)
    {

        $forms = new \Form;

        $form = $forms->whereSlug($form_slug)->firstOrFail();

        DB::beginTransaction();

        try {
        	$lead = new \Lead;

            $lead->form_id = $form->id;

            if($request->header('referer'))
                $lead->referer = $request->header('referer');

            $lead->remote_ip = $request->ip();

            if($request->utm_source)
                $lead->utm_source = $request->utm_source;

            if($request->utm_medium)
                $lead->utm_medium = $request->utm_medium;

            if($request->utm_term)
                $lead->utm_term = $request->utm_term;

            if($request->utm_content)
                $lead->utm_content = $request->utm_content;
            
            if($request->utm_campaign)
                $lead->utm_campaign = $request->utm_campaign;

            $lead->save();

            $input = $request->all();

            foreach($input as $name => $value) {
                $result_array = explode('_', $name);
                $prefix = array_shift($result_array);
                $key = implode('_', $result_array);
                if($prefix == 'zlfield') {
                    $leadValue = new \LeadValue;
                    $leadValue->lead_id = $lead->id;
                    $leadValue->key = $key;
                    $leadValue->value = $value;
                    $leadValue->save();
                }
            }
        }
        catch(\Exception $e) {
            DB::rollback();
            throw $e;
        }

        DB::commit();

        return Redirect::to($form->feedback_url);
    }
}
