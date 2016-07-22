<?php

namespace Zephia\ZLeader\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use SammyK\LaravelFacebookSdk\FacebookFacade as Facebook;
use Zephia\ZLeader\Model\Form;
use Zephia\ZLeader\Model\Lead;
use Zephia\ZLeader\Model\LeadValue;

class FbwebhookController extends Controller
{
    public function store(Request $request)
    {
        $access_token = 'EAAXOS2tqc5EBABZAX3NnTDSMoZB0u0p40nxodAzeoAuWWYgJZCMZAMYpJZCsmR3AyipkPQoR2NvYQr3Wo7kGuTPRvPvRF7Yc2l5o5u3PFUFYVjifASz3eY2VVjjACo5KLm2xlGBn1ufYTPdi1SeIU';
        Log::info('New Webhook:');

        if(is_array($request->entry)) {
            foreach($request->entry[0]['changes'] as $change_data) {
                if($change_data['field'] == 'leadgen') {
                    $leadgen = $change_data['value'];
                    
                    $lead_exists = Lead::where('fb_leadgen_id','=',$leadgen['leadgen_id'])->count();

                    if($lead_exists == 0) {
                        Log::info('New Lead!');

                        try {
                            $response = Facebook::get('/' . $leadgen['leadgen_id'], $access_token);
                            $lead_data = $response->getDecodedBody();
                            //Log::info('Lead values:');
                            //Log::info($lead_data);
                            $leadgen['lead_values'] = $lead_data['field_data'];
                        } catch(Exception $e) {
                            Log::error($e->getMessage());
                        }

                        try {
                            $response = Facebook::get('/' . $leadgen['form_id'], $access_token);
                            $form_data = $response->getDecodedBody();
                            //Log::info('Form data:');
                            //Log::info($form_data);
                            $leadgen['form_name'] = $form_data['name'];
                        } catch(Exception $e) {
                            Log::error($e->getMessage());
                        }

                        if(!empty($leadgen['ad_id'])) {
                            try {
                                $response = Facebook::get('/' . $leadgen['ad_id'] . '?fields=name,adset_id', $access_token);
                                $ad_data = $response->getDecodedBody();
                                //Log::info('Ad data:');
                                //Log::info($ad_data);
                                $leadgen['ad_name'] = $ad_data['name'];
                            } catch(Exception $e) {
                                Log::error($e->getMessage());
                            }

                            try {
                                $response = Facebook::get('/' . $ad_data['adset_id'] . '?fields=name', $access_token);
                                $adset_data = $response->getDecodedBody();
                                //Log::info('Ad data:');
                                //Log::info($ad_data);
                                $leadgen['adset_name'] = $adset_data['name'];
                            } catch(Exception $e) {
                                Log::error($e->getMessage());
                            }
                        }

                        Log::info($leadgen);

                        $form_name_exploded = explode(' ', $leadgen['form_name']);
                        $form_integration_prefix = array_shift($form_name_exploded);

                        $form = Form::where('fb_integration_prefix','like',$form_integration_prefix)->first();

                        Log::info($form);

                        if($form) {
                            $lead = new Lead;

                            $lead->form_id = $form->id;

                            $lead->fb_leadgen_id = $leadgen['leadgen_id'];

                            $lead->utm_source = 'facebook';

                            $lead->utm_medium = 'leadgen';

                            if(!empty($leadgen['ad_name']))
                                $lead->utm_term = $leadgen['ad_name'];

                            if($request->utm_content)
                                $lead->utm_content = $leadgen['adset_name'];

                            $lead->save();

                            foreach($leadgen['lead_values'] as $field) {
                                $leadValue = new LeadValue;
                                $leadValue->lead_id = $lead->id;
                                $leadValue->key = $field['name'];
                                $leadValue->value = $field['values'][0];
                                $leadValue->save();
                            }

                            $form_name_fields = explode(',', implode(' ', $form_name_exploded));
                            if(is_array($form_name_fields)) {
                                foreach($form_name_fields as $fields) {
                                    $field_data = explode('=', $fields);
                                    if(!empty(trim($field_data[0])) && !empty(trim($field_data[1]))) {
                                        $leadValue = new LeadValue;
                                        $leadValue->lead_id = $lead->id;
                                        $leadValue->key = trim($field_data[0]);
                                        $leadValue->value = trim($field_data[1]);
                                        $leadValue->save();
                                    }
                                }
                            }

                            Log::info('Lead saved');
                        } else {
                            Log::warning('Form "' . $form_integration_prefix . '" not found');

                            switch($leadgen['form_name']) {
                                case 'Plan Rombo - selector de modelos':
                                case 'Renault Kangoo - Venta Convencional':
                                case 'Megane 3 - Venta Convencional':
                                case 'Renault Duster - Venta Convencional':
                                case 'Sandero - Venta Convencional':
                                case 'Clio Mio -Venta Convencional':
                                case 'Clio Mio - Venta Convencional':
                                case 'Plan Rombo':
                                    $company_name = 'TAGLE';
                                    $emails = 'marketing@taglerenault.com.ar,sdonadio@taglerenault.com.ar';
                                    break;

                                case 'Note':
                                case 'Sentra':
                                case 'March':
                                case 'Frontier':
                                    $company_name = 'NIX';
                                    $emails = 'marketing@taglerenault.com.ar,sdonadio@taglerenault.com.ar';
                                    break;

                                case 'Autoplan 208 Adjudicado':
                                case 'Autoplan 208 Pea':
                                case 'Autoplan 2008':
                                case 'Autoplan 2008 Adjudicado':
                                case 'Auto Plan - Partner Patagónica':
                                case 'Autoplan - 208':
                                case 'Venta Directa - Partner':
                                case 'Venta Directa - 408':
                                case 'Venta Directa - 308':
                                case 'Venta Directa - Partner Furgón':
                                case 'Venta Directa - 208':
                                case 'Auto Plan -  Partner Furgón':
                                    $company_name = 'Avant';
                                    $emails = 'rramondelli@avant.com.ar,msaura@avant.com.ar';
                                    break;

                                case 'Motcor Planes Genérico':
                                case 'Fiat Palio - Plan Fiat':
                                    $company_name = 'Moctor';
                                    $emails = 'pereyram@motcor.com.ar';
                                    break;

                                default:
                                    Log::warning('Unknown form, reporting...');
                                    $company_name = 'Leadgen';
                                    $emails = 'andres@zephia.com.ar,cristian@zephia.com.ar';
                            }

                            Log::info('Sending "' . $leadgen['form_name'] . '" to ' . $emails);

                            $emails = explode(',', $emails);

                            Mail::send('ZLeader::lead.email-webhook-failed', ['lead' => $leadgen], function ($message) use ($emails, $company_name) {
                                $message->from(config('ZLeader.notification_sender_email_address'), $company_name);
                                $message->subject('Nueva consulta de Facebook');
                                //$message->replyTo($address);
                                foreach($emails as $email) {
                                    $message->to($email);
                                }
                            });

                            abort(404);
                        }
                    } else {
                        Log::warning('Lead exists!');
                    }
                }
            }
        }

        return $request->hub_challenge;
    }

    public function platform(Request $request)
    {
        return view('ZLeader::lead.fb-leadgen-platform');
    }
}