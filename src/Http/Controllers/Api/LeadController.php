<?php

namespace Zephia\ZLeader\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Exception;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Zephia\ZLeader\Model\Form;
use Zephia\ZLeader\Model\Lead;
use Zephia\ZLeader\Model\LeadValue;
 
class LeadController extends Controller
{
    public function store(Request $request, $form_slug)
    {

        $forms = new Form;

        $form = $forms->whereSlug($form_slug)->firstOrFail();

        DB::beginTransaction();

        try {
        	$lead = new Lead;

            $lead->form_id = $form->id;

            if($request->header('referer'))
                $lead->referer = $request->header('referer');

            $lead->remote_ip = $request->ip();

            $lead->user_agent = $request->header('User-Agent');;

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
                    $leadValue = new LeadValue;
                    $leadValue->lead_id = $lead->id;
                    $leadValue->key = $key;
                    $leadValue->value = $value;
                    $leadValue->save();
                }
            }

            if ($lead) {
                if (!empty($form->integration) && !empty($form->integration_id)) {
                    $integration = new $form->integration->class($lead->id);
                }
            }
        }
        catch(Exception $e) {
            DB::rollback();
            throw $e;
        }

        DB::commit();

        return Redirect::to($form->feedback_url);
    }

    public function releaseNotificationQueue(Request $request)
    {
        $notification_queue = Lead::where('notify','=',1)->get();

        foreach ($notification_queue as $lead) {
            if(!empty($lead->form->notification_emails) && !empty($lead->form->notification_subject)) {
                $emails = explode(',', $lead->form->notification_emails);

                if(is_array($emails)) {
                    // Internal e-mail notification
                    Mail::send('ZLeader::lead.email-internal-notification', ['lead' => $lead], function ($message) use ($emails, $lead) {
                        $message->from(config('ZLeader.notification_sender_email_address'), $lead->form->area->company->name);
                        $message->subject($lead->form->notification_subject);
                        //$message->replyTo($address);
                        foreach($emails as $email) {
                            $message->to($email);
                        }
                    });
                }
            }

            // User e-mail notification
            $email = $lead->getValueByKey('email');
            if(!empty($email ) && !empty($lead->form->user_notification_subject)) {
                Mail::send('ZLeader::lead.email-user-notification', ['lead' => $lead], function ($message) use ($email, $lead) {
                    $message->from(config('ZLeader.notification_sender_email_address'), $lead->form->area->company->name);
                    $message->subject($lead->form->user_notification_subject);
                    $message->to($email);
                });
            }

            //$lead->notify = 0;
            $lead->save();
        }
    }
}
