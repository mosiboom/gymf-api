<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakeService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'make service layer';

    private $base_path;
    private $tpl_path;
    private $namespace = "namespace App\Services";

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->base_path = app_path('Services/');
        $this->tpl_path = app_path('TPL/Service.php');
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $path = $this->argument('path');
        //创建的文件路径
        $create_file_path = $this->base_path . $path . '.php';
        if (file_exists($create_file_path)) {
            $this->error('文件已存在！');
            die();
        }
        //切割参数
        $cutting_arg = explode('/', $this->argument('path'));
        $count_cutting = count($cutting_arg);
        $service_path = str_replace($cutting_arg[$count_cutting - 1], '', $this->base_path . $path);
        if (!is_dir($service_path)) {
            mkdir($service_path, 0777, true);
        }
        copy($this->tpl_path, $create_file_path);
        $create_file_obj = file_get_contents($create_file_path);
        //大于1说明用户输入的是路径加文件名，如：Admin/TestService
        if ($count_cutting > 1) {
            $replace_namespace = $this->namespace;
            foreach ($cutting_arg as $k => $v) {
                if ($count_cutting - 1 == $k) break;
                $replace_namespace .= "\\" . $v;
            }
            $create_file_obj = str_replace($this->namespace, $replace_namespace, $create_file_obj);
        }
        //重命名类名
        $rename_class = str_replace("ServiceTPL", $cutting_arg[$count_cutting - 1], $create_file_obj);
        file_put_contents($create_file_path, $rename_class);
        $this->info('Service make successfully!');
    }
}
