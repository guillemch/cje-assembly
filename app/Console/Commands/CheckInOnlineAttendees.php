<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\Notifications\UserPickedupCredentials;
use Carbon\Carbon;

class CheckInOnlineAttendees extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'online:checkin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto check in all online attendees';

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
        $onlineAttendees = User::where('in_person', '=', 0)->get();
        $count = 0;

        if ($this->confirm('All online attendees will be notified. Do you wish to proceed?')) {

            $bar = $this->output->createProgressBar(count($onlineAttendees));
            $bar->start();

            foreach ($onlineAttendees as $user) {
                if ($user->credentials_pickedup_at !== null) {
                    $this->info($user->name . ' ' . $user->last_name . ' ya se ha acreditado');
                    continue;
                }

                $newPassword = str_random(8);
                $user->credentials_pickedup_at = Carbon::now();
                $user->password = bcrypt($newPassword);
                $user->save();

                $user->notify(new UserPickedupCredentials($user, $newPassword));

                $count++;
                $bar->advance();
            }

            $bar->finish();
            $this->line("\n\n$count online attendees were notified and checked in.");

        }
    }
}
