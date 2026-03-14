<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create-admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new admin user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $name = $this->ask('Enter admin name');
        $email = $this->ask('Enter admin email');
        do {
            $password = $this->secret('Enter admin password');
            $confirmPassword = $this->secret('Confirm admin password');
            if ($password !== $confirmPassword) {
                $this->error('Passwords do not match. Please try again.');
            }
        } while ($password !== $confirmPassword);
        
        User::create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password),
        ])->assignRole('Super Admin');
        $this->info('Admin user created successfully!');
        
        
  
    }
}
