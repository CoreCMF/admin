<?php

namespace CoreCMF\Admin\Commands;

use Illuminate\Console\Command;

use CoreCMF\Core\Support\Commands\Install;

class InstallCommand extends Command
{
    /**
     *  install class.
     * @var object
     */
    protected $install;
    /**
     * The name and signature of the console command.
     *
     * @var string
     * @translator laravelacademy.org
     */
    protected $signature = 'corecmf:admin:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'admin packages install';

    public function __construct(Install $install)
    {
        parent::__construct();
        $this->install = $install;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info($this->install->migrate());
        $this->info($this->install->publish('admin'));
        $this->info($this->install->seed(\CoreCMF\Admin\Databases\seeds\EntrustPermissionTableSeeder::class));
        $this->info($this->install->seed(\CoreCMF\Admin\Databases\seeds\EntrustRoleTableSeeder::class));
        $this->info($this->install->seed(\CoreCMF\Admin\Databases\seeds\AdminMenuTableSeeder::class));
        $this->info($this->install->seed(\CoreCMF\Admin\Databases\seeds\AdminConfigTableSeeder::class));
    }
}
