<?php

namespace App\Console\Commands;

use Exception;
use App\Models\Email;
use App\Models\Status;
use App\Models\Template;
use App\Models\Subscriber;
use App\Mail\SubscriberEmail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
class SendEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-emails {subscribersPerRequest?} {template_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send schedule emails to subscribers';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $subscribersPerRequest = $this->argument('subscribersPerRequest');
        $template_id = $this->argument('template_id');
        $record = Template::find($template_id);
        $subscribers = Subscriber::where('is_subscribed', 1)->get();

        if ($subscribers->isNotEmpty()) {
            $count = 0;
            foreach ($subscribers as $subscriber) {
                if ($count <= $subscribersPerRequest || empty($subscribersPerRequest)) {
                    $received = Email::where('template_id', $template_id)
                        ->where('subscriber_id', $subscriber->id)
                        ->where('status', 1)
                        ->first();
                    if (empty($received)) {
                        try {
                            Mail::to($subscriber->email)->send(new SubscriberEmail($record, $subscriber));
                            Email::updateOrCreate(
                                [
                                    'subscriber_id' => $subscriber->id,
                                    'template_id' => $template_id,
                                ],
                                ['status' => 1] // Success
                            );
                            $status = Status::where('name', 'sent')->first();
                            if ($status) {
                                $status->update([
                                    'count' => $status->count + 1
                                ]);
                            }
                        } catch (Exception $e) {
                            Email::updateOrCreate(
                                [
                                    'subscriber_id' => $subscriber->id,
                                    'template_id' => $template_id,
                                ],
                                ['status' => 0] // Failed
                            );

                            $status = Status::where('name', 'failed')->first();
                            if ($status) {
                                $status->update([
                                    'count' => $status->count + 1
                                ]);
                            }
                            throw $e;
                        }
                    }
                    $count++;
                }
            }
        }
    }
}
