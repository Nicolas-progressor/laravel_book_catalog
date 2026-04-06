<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateTestUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-test-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a test user with email test@example.com and password password123';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user = User::where('email', 'test@example.com')->first();

        if (!$user) {
            $user = new User();
            $user->email = 'test@example.com';
        }

        $user->name = 'Тестовый Пользователь';
        $user->username = 'Тестовый Пользователь';
        $user->roles = ['ROLE_USER'];
        $user->password = Hash::make('password123');

        $user->save();

        $this->info('Пользователь создан: test@example.com / password123');
        return Command::SUCCESS;
    }
}
