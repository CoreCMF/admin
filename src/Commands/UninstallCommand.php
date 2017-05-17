<?php

namespace CoreCMF\admin\Commands;

use Illuminate\Console\Command;

use CoreCMF\core\Commands\Uninstall;
class UninstallCommand extends Command
{
    use Uninstall;
    /**
     * The name and signature of the console command.
     *
     * @var string
     * @translator laravelacademy.org
     */
    protected $signature = 'corecmf:admin:uninstall';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'admin packages uninstall';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->dropTable('admin_menus');
    }
}