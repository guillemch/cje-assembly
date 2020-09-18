<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\User;

class ImportVoters extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:voters';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import voters';

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
        $contents = Storage::get('voters/voters.csv');

        $lines = explode("\n", $contents);

        foreach($lines as $line) {
            if (empty($line)) continue;

            $fields = explode(";", $line);

            $user = new User;
            $user->type = $fields[1];
            $user->group_id = ($fields[0]) ? $fields[0] : null;
            $user->name = $fields[3];
            $user->last_name = $fields[4];
            $user->email = $fields[5];
            $user->phone = ($fields[6]) ? '34' . str_replace(" ", "", $fields[6]) : '';
            $user->id_card = '';
            $user->votes = $fields[7];
            $user->in_person = 1;
            $user->password = bcrypt(str_random(40));
            $user->save();
        }

        $this->line('Voters imported successfully.');
    }
}
