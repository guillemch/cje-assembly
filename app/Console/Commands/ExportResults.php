<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Amendment;

class ExportResults extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export:results';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export results';

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
        $amendments = Amendment::all();
        $this->info('Enmienda,Resultado,1/A Favor,2/En Contra,3/Abstención,4,5,Cerrada');
        foreach($amendments as $amendment) {
            $winner = ($amendment->results['winner']) ? $amendment['option_' + $amendment->results['winner']] : '--';
            $this->info("{$amendment->name},{$winner},{$amendment->weighted['option_1']},{$amendment->weighted['option_2']},{$amendment->weighted['option_3']},{$amendment->weighted['option_4']},{$amendment->weighted['option_5']},{$amendment->closed_at}");
        }
    }
}
