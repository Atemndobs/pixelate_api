<?php

namespace App\Console\Commands\storage;

use Cog\Laravel\Love\ReactionType\Models\ReactionType;
use Illuminate\Console\Command;

class like extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reaction {types}';

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
        $input = $this->argument('types');

        $types = explode(',', $input);

        foreach ($types as $type){
            if ( ReactionType::where(['name' => $type])->count() > 0) {
                $this->info("{$type} already Exists");
                continue;
            }else{
                $this->info("Creating reaction type:  {$type}");
                $createTypes = $this->createTypes($type);
                ReactionType::insert($createTypes);
            }
        }

        $data = [];
      $allTypes =  ReactionType::all()->toArray();

      foreach ($allTypes as $allType){
          $data[] = [
              'id' => $allType['id'],
              'name' => $allType['name']
          ];
      }

      $headers = ['id', 'name'];
      $this->table($headers , $data);

        return 0;
    }

    /**
     * @param array|string $type
     * @return array[]
     */
    public function createTypes($type): array
    {
        $createTypes = [
            [
                'name' => $type,
                'mass' => 1,
                'created_at' => new \DateTime('now'),
                'updated_at' => new \DateTime('now')
            ],

            [
                'name' => "Dis{$type}",
                'mass' => -1,
                'created_at' => new \DateTime('now'),
                'updated_at' => new \DateTime('now')

            ]
        ];
        return $createTypes;
    }
}
