<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DeleteDatabase extends Command
{
   /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Drop Database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $connection = config('database.default');
        $config = config("database.connections.$connection");
        $database = $config['database'];
        $host = $config['host'];
        $username = $config['username'];
        $password = $config['password'];

        $this->info('Deleting Database...');
        $this->dropDatabase($database, $host, $username, $password);
    }

    public function dropDatabase($database, $host, $username, $password)
    {
        $pdo = new \PDO("mysql:host=$host", $username, $password);
        $pdo->exec("DROP DATABASE IF EXISTS `$database`");
    }
}
