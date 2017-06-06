<?php

namespace CoreCMF\admin\Commands;

use Illuminate\Console\Command;

use CoreCMF\core\Commands\Install;

class InstallCommand extends Command
{
    use Install;
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
        $this->migrate();
        $this->publish('seeds');
        $this->dumpAutoload();
        $this->seed('MenuTableSeeder');
    }
}
