<?php

namespace Database\Seeders;

use Hash;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::create([
            'name' => 'Administrator',
            'email' => 'admin@sms.to',
            'password' => Hash::make('fGZLthkXDHxLnr.4FrgC'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ]);
    }
}
