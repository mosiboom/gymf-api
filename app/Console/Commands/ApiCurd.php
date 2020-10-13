<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ApiCurd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tpl:curd {name} {--ctl=} {--model=} {--request=} {--service=} {--same=} {--api}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create curd tool for base development';

    protected $tpl_name;

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
     * @return void
     */
    public function handle()
    {
        $this->tpl_name = $this->argument('name');
        $same = $this->option('same');
        $api = $this->option('api');
        $api_option = $api ? '--api' : '';
        if ($same) {
            exec("php artisan make:service {$same}'/'{$this->tpl_name}Service");
            $this->info('Service create successfully!');
            exec("php artisan make:controller {$same}'/'{$this->tpl_name}Controller ${api_option}");
            $this->info('Controller create successfully!');
            exec("php artisan make:model 'Models'/{$same}'/'{$this->tpl_name}");
            $this->info('Model create successfully!');
            exec("php artisan tpl:request {$same}'/'{$this->tpl_name}Request");
            $this->info('Request create successfully!');

        } else {
            $ctl = $this->option('ctl') ? "{$this->option('ctl')}/" : '';
            $model = $this->option('model') ? "{$this->option('model')}/" : 'Models/';
            $request = $this->option('request') ? "{$this->option('request')}/" : '';
            $service = $this->option('service') ? "{$this->option('service')}/" : '';
            exec("php artisan make:service {$service}{$this->tpl_name}Service");
            $this->info('Service create successfully!');
            exec("php artisan make:controller {$ctl}{$this->tpl_name}Controller ${api_option}");
            $this->info('Controller create successfully!');
            exec("php artisan make:model {$model}{$this->tpl_name}");
            $this->info('Model create successfully!');
            exec("php artisan tpl:request {$request}{$this->tpl_name}Request");
            $this->info('Request create successfully!');
        }
    }
}
