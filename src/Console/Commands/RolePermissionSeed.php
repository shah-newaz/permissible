<?php

namespace Shahnewaz\Permissible\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class RolePermissionSeed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissible:seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seeds roles and permissions. Creates super user.';

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
    public function handle()
    {
        Artisan::call('db:seed', ['--class' => 'Shahnewaz\Permissible\Database\Seeder\RolePermissionSeeder']);
        $this->info('Permissible roles and permissions seeded!');
        return Null;
    }
}
