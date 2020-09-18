<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Amendment;

class ImportAmendments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:amendments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import amendments';

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
        $contents = Storage::get('votes/votes.csv');

        $lines = explode("\n", $contents);

        foreach($lines as $vote) {
            $fields = explode(";", $vote);
            // dd($fields);

            if (empty($fields[1])) continue;

            $amendment = new Amendment;
            $amendment->id = $fields[0];
            $amendment->name = $fields[1];
            $amendment->description = $fields[3];
            $amendment->joint_with = ($fields[2]) ? $fields[2] : null;
            $amendment->option_1 = $fields[4];
            $amendment->option_2 = $fields[5];
            $amendment->option_3 = $fields[6];
            $amendment->option_4 = ($fields[7]) ? $fields[7] : null;
            $amendment->option_5 = ($fields[8]) ? $fields[8] : null;
            $amendment->save();
        }

        $this->line('Votes imported successfully.');
    }
}
