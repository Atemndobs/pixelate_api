<?php

namespace App\Console\Commands\reset;

use Doctrine\DBAL\Schema\Schema;
use Illuminate\Console\Command;

class table extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset:table {table}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Truncate Database for table';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->name = 'table';
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $table = $this->argument('table');

        $full = \DB::table($table)->select('*')->count();
        $this->info( "Actual records in {$table} : {$full}" );
        // Schema::disableForeignKeyConstraints();

        \Schema::disableForeignKeyConstraints();

        if ($full === 0) {
            $this->info("Nothing to Clean !");
            return 0;
        }
        $this->info("Cleaning the database table:  {$table}");
        \DB::table($table)->truncate();
        \Schema::enableForeignKeyConstraints();

        $empty = \DB::table($table)->select('*')->count();
        $this->info( "Table Empty, records in {$table} : {$empty}" );

        return 0;

    }
}
