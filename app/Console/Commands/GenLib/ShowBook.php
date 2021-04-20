<?php

namespace App\Console\Commands\GenLib;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ShowBook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'genlib:show {ids}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'book id or md5';

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
     * @return int
     */
    public function handle()
    {
        $ids = (integer)$this->argument('ids');
        $response = Http::get("http://libgen.rs/json.php");
        dd($response->body());
    }
}
