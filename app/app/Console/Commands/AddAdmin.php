<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class AddAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:add-admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add ROLE_ADMIN to user test@example.com';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user = User::where('email', 'test@example.com')->first();

        if (!$user) {
            $this->error('Пользователь не найден');
            return Command::FAILURE;
        }

        $roles = $user->roles ?? [];
        if (!in_array('ROLE_ADMIN', $roles)) {
            $roles[] = 'ROLE_ADMIN';
            $user->roles = $roles;
            $user->save();
        }

        $this->info('Пользователю test@example.com присвоена роль ROLE_ADMIN');
        return Command::SUCCESS;
    }
}
