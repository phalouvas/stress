<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
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
            'endpoint_webapp' => 'https://smsto.space',
            'endopoint_msms' => 'https://api.smsto.space',
            'endpoint_msas' => 'https://auth.smsto.space'
        ]);
    }
}
