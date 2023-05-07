<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Status;
use App\Models\Template;
use App\Models\Subscriber;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsOverview extends BaseWidget
{
    protected function getCards(): array
    {
        $statuses = Status::all();

        if ($statuses->isEmpty()) {
            $statuses = ['spam', 'open', 'failed', 'sent'];

            foreach ($statuses as $status) {
                Status::create([
                    'name' => $status,
                    'count' => 0
                ]);
            }
        }
        // All templates
        $templates = Template::all()->count();
        // scheduled templates
        $s_templates = Template::where('status', 'scheduled')->get()->count();
        // sent emails
        $sent_emails = Status::where('name', 'sent')->first()->count;
        // failed emails
        $failed_emails = Status::where('name', 'failed')->first()->count;
        // spam emails
        // $spam_emails = Status::where('name', 'spam')->first()->count;
        // open emails
        $open_emails = Status::where('name', 'open')->first()->count;

        return [
            // Total Subscribers
            Card::make('Total Subscribers',$this->total_subs_stats()->count)
                ->description($this->total_subs_stats()->text)
                ->descriptionIcon($this->total_subs_stats()->icon)
                ->chart($this->getLast7DaysData(Subscriber::class))
                ->color('primary'),

            // Active Subscribers
            Card::make('Active Subscribers', $this->active_subs_stats()->count)
                ->description($this->active_subs_stats()->text)
                ->descriptionIcon($this->active_subs_stats()->icon)
                ->chart($this->getLast7DaysData(Subscriber::class, true))
                ->color('success'),

            // Inactive Subscribers
            Card::make('Inactive Subscribers', $this->inactive_subs_stats()->count)
                ->description($this->inactive_subs_stats()->text)
                ->descriptionIcon($this->inactive_subs_stats()->icon)
                ->chart($this->getLast7DaysData(Subscriber::class, false))
                ->color('danger'),

            // Admins
            Card::make('Admins', $this->admin_stats()->count)
                ->description($this->admin_stats()->text)
                ->descriptionIcon($this->admin_stats()->icon)
                ->chart($this->getLast7DaysData(User::class))
                ->color('secondary'),

            // Total Email Templates
            Card::make('Total Email Templates', $templates)
                // ->description('10 decreased')
                // ->descriptionIcon('heroicon-s-trending-down')
                ->chart([7, 2, 10, 3, 15, 4, 1])
                ->color('warning'),

            // Scheduled Email Templates
            Card::make('Scheduled Email Templates', $s_templates)
                // ->description('10 increased')
                // ->descriptionIcon('heroicon-s-trending-up')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('warning'),

            // Total Email Sent
            Card::make('Total Email Sent', $sent_emails + $failed_emails)
                // ->description('10 decreased')
                // ->descriptionIcon('heroicon-s-trending-down')
                ->chart([7, 2, 10, 3, 15, 4, 1])
                ->color('info'),

            // Success Email
            Card::make('Success Email', $sent_emails)
                // ->description('10 increased')
                // ->descriptionIcon('heroicon-s-trending-up')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),

            // Failed Email
            Card::make('Failed Email', $failed_emails)
                // ->description('10 increased')
                // ->descriptionIcon('heroicon-s-trending-up')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('danger'),

            // Open Email
            Card::make('Open Email', $open_emails)
                // ->description('10 increased')
                // ->descriptionIcon('heroicon-s-trending-up')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),

           /*  // Spam Email
            Card::make('Spam Email', $spam_emails)
                // ->description('10 increased')
                // ->descriptionIcon('heroicon-s-trending-up')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('danger'), */
        ];
    }

    protected function total_subs_stats(){
        // Get count
        $total_subs_count = Subscriber::all()->count();
        // Get yesterday's and today's subscribers
        $yesterday = now()->subDay()->startOfDay();
        $today = now()->startOfDay();

        $subscribers_yesterday = Subscriber::where('created_at', '>=', $yesterday)
            ->where('created_at', '<', $today)
            ->get();

        $subscribers_today = Subscriber::where('created_at', '>=', $today)
            ->get();

        // Calculate the difference between yesterday's and today's subscriber counts
        $count_yesterday = $subscribers_yesterday->count();
        $count_today = $subscribers_today->count();
        $difference = $count_today - $count_yesterday;

       // Display the appropriate count and increase/decrease/remained the same text and icon
       return $this->difference($difference, $total_subs_count);
    }

    protected function active_subs_stats(){
        // Get count
        $active_subs = Subscriber::where('is_subscribed', 1)->get()->count();
        // Get yesterday's and today's subscribers
        $yesterday = now()->subDay()->startOfDay();
        $today = now()->startOfDay();

        $subscribers_yesterday = Subscriber::where('updated_at', '>=', $yesterday)
            ->where('updated_at', '<', $today)->where('is_subscribed', 1)
            ->get();

        $subscribers_today = Subscriber::where('updated_at', '>=', $today)->where('is_subscribed', 1)
            ->get();

        // Calculate the difference between yesterday's and today's subscriber counts
        $count_yesterday = $subscribers_yesterday->count();
        $count_today = $subscribers_today->count();
        $difference = $count_today - $count_yesterday;

        // Display the appropriate count and increase/decrease/remained the same text and icon
        return $this->difference($difference, $active_subs);
    }

    protected function inactive_subs_stats(){
        // Get count
        $inactive_subs = Subscriber::where('is_subscribed', 0)->get()->count();
        // Get yesterday's and today's subscribers
        $yesterday = now()->subDay()->startOfDay();
        $today = now()->startOfDay();

        $subscribers_yesterday = Subscriber::where('updated_at', '>=', $yesterday)
            ->where('updated_at', '<', $today)->where('is_subscribed', 0)
            ->get();

        $subscribers_today = Subscriber::where('updated_at', '>=', $today)->where('is_subscribed', 0)
            ->get();

        // Calculate the difference between yesterday's and today's subscriber counts
        $count_yesterday = $subscribers_yesterday->count();
        $count_today = $subscribers_today->count();
        $difference = $count_today - $count_yesterday;

        // Display the appropriate count and increase/decrease/remained the same text and icon
        return $this->difference($difference, $inactive_subs);
    }

    protected function admin_stats(){
        // Get count
        $admin_users = User::all()->count();
        // Get yesterday's and today's subscribers
        $yesterday = now()->subDay()->startOfDay();
        $today = now()->startOfDay();

        $subscribers_yesterday = User::where('created_at', '>=', $yesterday)
            ->where('created_at', '<', $today)
            ->get();

        $subscribers_today = User::where('created_at', '>=', $today)
            ->get();

        // Calculate the difference between yesterday's and today's subscriber counts
        $count_yesterday = $subscribers_yesterday->count();
        $count_today = $subscribers_today->count();
        $difference = $count_today - $count_yesterday;

        // Display the appropriate count and increase/decrease/remained the same text and icon
        return $this->difference($difference, $admin_users);
    }

    protected function difference($diff, $count){
        // Display the appropriate count and increase/decrease/remained the same text and icon
        if ($diff > 0) {
            $text = "$diff increased";
            $icon = "heroicon-s-trending-up";
        } elseif ($diff < 0) {
            $text = "$diff decreased";
            $icon = "heroicon-s-trending-down";
        } else {
            $text = "$diff remained the same";
            $icon = "heroicon-s-minus";
        }

        $total_subs_stats = new \stdClass;
        $total_subs_stats->text = $text;
        $total_subs_stats->icon = $icon;
        $total_subs_stats->count = $count;

        return $total_subs_stats;
    }

    protected function getLast7DaysData($model, $is_active = null) {
        $data = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->startOfDay();
            if($is_active === null){
                $count = $model::where('created_at', '>=', $date)
                ->where('created_at', '<', $date->copy()->endOfDay())
                ->count();
            }else{
                $count = $model::where('created_at', '>=', $date)
                ->where('created_at', '<', $date->copy()->endOfDay())
                ->where('is_subscribed', $is_active)
                ->count();
            }
            $data[] = $count;
        }

        return $data;
    }
}
