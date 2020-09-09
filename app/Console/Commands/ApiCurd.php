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
    protected $signature = 'tpl:APICurd {name} {--ctl=} {--model=} {--request=} {--service=} {--same=}';

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
        if ($same) {
            $this->makeService($this->tpl_name, $same . "/");
            exec("php artisan make:controller {$same}'/'{$this->tpl_name}Controller --api");
            exec("php artisan make:model 'Models'/{$same}'/'{$this->tpl_name}");
            exec("php artisan tpl:request {$same}'/'{$this->tpl_name}Request");
        } else {
            $ctl = $this->option('ctl') ? "{$this->option('ctl')}/" : '';
            $model = $this->option('model') ? "{$this->option('model')}/" : 'Models/';
            $request = $this->option('request') ? "{$this->option('request')}/" : '';
            $service = $this->option('service') ? "{$this->option('service')}/" : '';
            $this->makeService($this->tpl_name, $service);
            exec("php artisan make:controller {$ctl}{$this->tpl_name}Controller --api");
            exec("php artisan make:model {$model}{$this->tpl_name}");
            exec("php artisan tpl:request {$request}{$this->tpl_name}Request");
        }


    }

    /**
     * 生成service层
     * @param $name
     * @param $service
     */
    public function makeService($name, $service)
    {
        $name = $service . $name;
        $request_path = app_path('/Services/');
        $tpl_path = app_path('/Services/TPLService.php');
        $namespace = "namespace App\Services";
        $create_path = $request_path . $name . 'Service.php';
        $rename = explode('/', $name);
        $count = count($rename);
        $dir = str_replace($rename[$count - 1], '', $request_path . $name);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        if (!is_dir($request_path . $service)) {
            mkdir($request_path . $service, 0777, true);
        }
        copy($tpl_path, $create_path);
        $create_obj = file_get_contents($create_path);
        if ($count > 1) {
            $replace_namespace = $namespace;
            foreach ($rename as $k => $v) {
                if ($count - 1 == $k) break;
                $replace_namespace .= "\\" . $v;
            }
            $create_obj = str_replace($namespace, $replace_namespace, $create_obj);
        }
        $rename_str = str_replace("TPLService", $rename[count($rename) - 1], $create_obj);
        file_put_contents($create_path, $rename_str);
    }
}
