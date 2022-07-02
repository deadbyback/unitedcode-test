<?php

namespace App\Console\Commands;

use App\Console\Models\LaravelNewsImporter;
use Illuminate\Console\Command;

class ImportLaravelNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'news:import {--monthNumber=4}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $month = $this->option('monthNumber');
        $url = 'https://laravel-news.com/blog';

        $importer = new LaravelNewsImporter($url, $month);
        $importer->process();
    }
}
