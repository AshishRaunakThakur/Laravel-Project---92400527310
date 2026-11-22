<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminCommand extends Command
{
    protected $signature = 'campus:make-admin {--name= : Administrator name} {--email= : Administrator email}';
    protected $description = 'Create or promote a CampusCare administrator account interactively';

    public function handle(): int
    {
        $name = trim((string) ($this->option('name') ?: $this->ask('Administrator name')));
        $email = strtolower(trim((string) ($this->option('email') ?: $this->ask('Administrator email'))));
        $existing = User::where('email', $email)->first();
        $emailRules = ['required', 'email', 'max:190'];
        if (! $existing) {
            $emailRules[] = 'unique:users,email';
        }

        $validator = validator(['name' => $name, 'email' => $email], [
            'name' => ['required', 'string', 'max:120'],
            'email' => $emailRules,
        ]);
        if ($validator->fails()) {
            $this->error($validator->errors()->first());
            return self::FAILURE;
        }

        $password = $this->secret('Password (at least 8 characters)');
        $confirmation = $this->secret('Confirm password');
        if (strlen((string) $password) < 8 || $password !== $confirmation) {
            $this->error('Passwords must match and contain at least 8 characters.');
            return self::FAILURE;
        }

        if ($existing) {
            $existing->forceFill([
                'name' => $name,
                'password' => Hash::make($password),
                'role' => 'admin',
                'is_active' => true,
            ])->save();
            $this->info('Existing account promoted to administrator and password updated.');
        } else {
            User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'role' => 'admin',
                'is_active' => true,
            ]);
            $this->info('Administrator account created. Sign in using the email you entered.');
        }

        return self::SUCCESS;
    }
}
