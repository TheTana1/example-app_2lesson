<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
//    protected $signature = 'app:test-command {seria}';
    protected $signature = 'app:test-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::query()->get();
        dd($users);

//        dd($this->argument('seria'));
//        foreach ($users as $key => $user) {
//            $user->name = 'Test User ' . $key;
//            $user->save();
//        }

    }
}
