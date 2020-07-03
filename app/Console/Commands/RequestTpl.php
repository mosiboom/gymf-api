<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RequestTpl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tpl:request {path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'make request tpl for rapid development form validation';

    private $request_path;
    private $tpl_path;
    private $namespace = "namespace App\Http\Requests";

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->request_path = app_path('/Http/Requests/');
        $this->tpl_path = app_path('/Http/Requests/RequestTpl.php');
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $create_path = $this->request_path . $this->argument('path') . '.php';
        $rename = explode('/', $this->argument('path'));
        $count = count($rename);
        $dir = str_replace($rename[$count - 1], '', $this->request_path . $this->argument('path'));
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        copy($this->tpl_path, $create_path);
        $create_obj = file_get_contents($create_path);
        if ($count > 1) {
            $replace_namespace = $this->namespace;
            foreach ($rename as $k => $v) {
                if ($count - 1 == $k) break;
                $replace_namespace .= "\\" . $v;
            }
            $create_obj = str_replace($this->namespace, $replace_namespace, $create_obj);
        }
        $rename_str = str_replace("RequestTpl", $rename[count($rename) - 1], $create_obj);
        file_put_contents($create_path, $rename_str);
        $this->info('request模板创建成功！');
    }
}
