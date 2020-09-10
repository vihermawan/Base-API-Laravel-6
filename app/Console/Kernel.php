<?php

namespace App\Console;

use Illuminate\Support\Facades\DB;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Carbon;

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
        // $schedule->call(function () {
        //     $datenow = Carbon::now();   
        //     $event = DB::table('event')
        //             ->join('detail_event','event.id_detail_event','=','detail_event.id_detail_event')
        //             ->select('event.*','detail_event.*')
        //             ->where('end_event','>=',$datenow)
        //             ->update(['id_status' => 2]);
        // })->everyMinute();
        $schedule->call(function () {
            DB::table('confirm_signer')->where('created_at', '<', Carbon::now()->subMinutes(30)->toDateTimeString())->delete();
        })->everyMinute();

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
