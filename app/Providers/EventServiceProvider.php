<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        'jdavidbakr\MailTracker\Events\EmailSentEvent' => [
            'App\Listeners\EmailSent',
        ],
        'jdavidbakr\MailTracker\Events\ViewEmailEvent' => [
            'App\Listeners\EmailViewed',
        ],
        'jdavidbakr\MailTracker\Events\EmailDeliveredEvent' => [
            'App\Listeners\EmailDelivered',
        ],
        'jdavidbakr\MailTracker\Events\ComplaintMessageEvent' => [
            'App\Listeners\EmailComplaint',
        ],
        'jdavidbakr\MailTracker\Events\PermanentBouncedMessageEvent' => [
            'App\Listeners\BouncedEmail',
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
