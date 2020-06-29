<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AdminModel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new admin model for permissions manage';

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
        //生成管理员权限表的模型、初始化时使用
        system('php artisan make:model Models/Admin/AdminUser');
        $this->info('用户模型创建成功');
        system('php artisan make:model Models/Admin/AdminRole');
        $this->info('角色模型创建成功');
        system('php artisan make:model Models/Admin/AdminPermission');
        $this->info('权限创建成功');
        system('php artisan make:model Models/Admin/AdminMenu');
        $this->info('菜单模型创建成功');
        system('php artisan migrate:reset');
        system('php artisan migrate');
        $this->info('权限数据表创建成功');
    }
}
