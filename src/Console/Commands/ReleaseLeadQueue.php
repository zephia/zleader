<?php

namespace Zephia\ZLeader\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Zephia\ZLeader\Model\Lead;
use Maknz\Slack\Client as SlackClient;

class ReleaseLeadQueue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'autocity:release-queue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Release Lead Notification Queue';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $notification_queue = Lead::where('notify', '=', 1)->get();

        $slack_client = new SlackClient(env('ZLEADER_SLACK_NOTIFICATIONS_WEBHOOK_URL',[
            'username' => 'Autocity Core',
            'channel' => '#autocity-alerts',
            'icon' => ':cloud:',
        ]));

        foreach ($notification_queue as $lead) {

            if ($lead) {
                if (!empty($lead->form->integration) && !empty($lead->form->integration_id)) {
                    try {
                        $integration = new $lead->form->integration->class($lead->id, json_decode($lead->form->integration_options, true));
                    } catch (\Exception $e) {
                        Log::error($e->getMessage());
                        $slack_client->send('Integration error: ' . $e->getMessage() . ' - ' . '`' . $lead->toJson() . '`');
                        $lead->notify = 0;
                        $lead->save();
                    }
                }

                if (!empty($lead->form->notification_emails) && !empty($lead->form->notification_subject)) {
                    $emails = explode(',', $lead->form->notification_emails);

                    if (is_array($emails)) {
                        // Internal e-mail notification
                        try {
                            Mail::send('ZLeader::lead.email-internal-notification', ['lead' => $lead], function ($message) use ($emails, $lead) {
                                $message->from(config('ZLeader.notification_sender_email_address'), $lead->form->area->company->name);
                                $message->subject($lead->form->notification_subject);

                                $reply_email = trim($lead->getValueByKey('email'));
                                $message->replyTo($reply_email);

                                foreach ($emails as $email) {
                                    if (!empty(trim($email))) {
                                        $message->to(trim($email));
                                    }
                                }
                            });
                        } catch (\Exception $e) {
                            Log::error($e->getMessage());
                            $slack_client->send('Internal e-mail notification error: ' . $e->getMessage() . ' - ' . '`' . $lead->toJson() . '`');
                        }
                    }
                }

                try {
                    // User e-mail notification
                    $email = trim($lead->getValueByKey('email'));
                    if (!empty($email) && !empty($lead->form->user_notification_subject)) {
                        Mail::send('ZLeader::lead.email-user-notification', ['lead' => $lead], function ($message) use ($email, $lead) {
                            $message->from(config('ZLeader.notification_sender_email_address'), $lead->form->area->company->name);
                            $message->subject($lead->form->user_notification_subject);
                            $message->to($email);
                        });
                    }
                } catch (\Exception $e) {
                    Log::error($e->getMessage());
                    $slack_client->send('User e-mail notification error: ' . $e->getMessage() . ' - '. '`' . $lead->toJson() . '`');
                    $lead->notify = 0;
                    $lead->save();
                }
            }

            $lead->notify = 0;
            $lead->save();
        }
    }
}