<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\RouterService;
use Faker\Generator as Faker;

class RouterGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'router:generate {count=1}';

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
    public function handle(Faker $faker)
    {
        $count = $this->argument('count');
        $total=$count;

        $routerService = new RouterService();
        if($count<100){
            while($count-- >0){
              $this->createRouter($faker, $routerService);
            }
            echo "Router data generated for $total times \n";
        }else{
            echo "Its  not good idea to generate more than 100 \n";
        }
    }

    private function createRouter($faker, $routerService){
        $result= $routerService->createRouter(
                    $faker->word(18),
                    $faker->word(14),
                    $faker->ipv4(),
                    $faker->macAddress()
            );
    }
}
