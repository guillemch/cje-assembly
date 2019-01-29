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
            $user->type = $fields[0];
            $user->group_id = $fields[1];
            $user->name = $fields[2];
            $user->last_name = $fields[3];
            $user->email = $fields[4];
            $user->phone = $fields[5];
            $user->id_card = $fields[6];
            $user->password = bcrypt(str_random(40));
            $user->save();
        }

        $this->line('Voters imported successfully.');
    }
}
