<?php

namespace Database\Seeders;

use App\Models\Test;
use Illuminate\Database\Seeder;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Test::where('id', '>', '0')->delete();

        Test::create([
            'name' => "Get Balance",
            'type' => "get",
            'environment' => 'endpoint_msas',
            'path' => '/api/balance'
        ]);

        Test::create([
            'name' => "Verify Number",
            'type' => "post",
            'environment' => 'endpoint_webapp',
            'path' => '/api/v1/verify/number',
            'payload' => json_encode(['to' => '+35794000000'])
        ]);

        Test::create([
            'name' => "Create list",
            'type' => "post",
            'environment' => 'endpoint_webapp',
            'path' => '/v1/people/lists/create',
            'payload' => json_encode(['name' => 'Stress Test', 'description' => 'For stress test'])
        ]);

        Test::create([
            'name' => "Get Lists",
            'type' => "get",
            'environment' => 'endpoint_webapp',
            'path' => '/v1/people/lists',
        ]);

        Test::create([
            'name' => "Create contact",
            'type' => "post",
            'environment' => 'endpoint_webapp',
            'path' => '/v1/people/contacts/create',
            'payload' => json_encode(['phone' => '+35794000000'])
        ]);

        Test::create([
            'name' => "Get Contacts",
            'type' => "get",
            'environment' => 'endpoint_webapp',
            'path' => '/v1/people/contacts',
        ]);

        Test::create([
            'name' => "Get messages",
            'type' => "get",
            'environment' => 'endpoint_msms',
            'path' => '/messages',
        ]);
    }
}
