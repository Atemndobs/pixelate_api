<?php

namespace App\Console\Commands\storage;

use Illuminate\Console\Command;

class clear extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:assets {folder}';

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
     * @return int
     */
    public function handle()
    {
        $folder = $this->argument('folder');

        $files = \Storage::allFiles('public');

        foreach ($files as $file) {
            \Storage::delete($file);
        }

       // dump($files);
        $this->info( "Emptied Folder  : {$folder}" );
        return 0;
    }
}
