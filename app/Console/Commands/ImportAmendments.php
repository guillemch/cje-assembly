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
            $amendment = new Amendment;
            $amendment->name = $vote;
            $amendment->option_1 = 'A favor';
            $amendment->option_2 = 'En contra';
            $amendment->option_3 = 'Abstención';
            $amendment->save();
        }

        $this->line('Votes imported successfully.');
    }
}
