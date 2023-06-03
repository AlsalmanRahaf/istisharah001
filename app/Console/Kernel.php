<?php

namespace App\Console;

use App\job\BookingReminderJob;
use App\job\TestJob;
use App\job\Unread_Support_Ads;
use App\job\UnReadChatconsultation;
use App\job\UnReadChatConsultationAdmin;
use App\job\UnreadRequestConsultantChat;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
//        $schedule->job(new NotSeenNotification())->everyMinute();

//        $schedule->job(new UnReadChatconsultation())->everyMinute();
//        $schedule->job(new UnReadChatConsultationAdmin())->everyMinute();
//        $schedule->job(new UnreadRequestConsultantChat())->everyMinute();
//        $schedule->job(new Unread_Support_Ads())->everyMinute();

      $schedule->job(new BookingReminderJob())->everyFiveMinutes();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
