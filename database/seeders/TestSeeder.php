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
            'payload' => json_encode(['name' => 'Stress', 'description' => 'For stress test'])
        ]);

        Test::create([
            'name' => "Get Lists",
            'type' => "get",
            'environment' => 'endpoint_webapp',
            'path' => '/v1/people/lists',
        ]);

        Test::create([
            'name' => "Import contacts",
            'type' => "post",
            'environment' => 'endpoint_webapp',
            'path' => '/v1/people/contacts/import',
            'payload' => '{}'
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

        Test::create([
            'name' => "Estimate Single Message",
            'type' => "post",
            'environment' => 'endpoint_msms',
            'path' => '/sms/estimate',
            'payload' => json_encode(['to' => '+35794000000', 'message' => 'Stress test single message.',])
        ]);

        Test::create([
            'name' => "Send Single Message",
            'type' => "post",
            'environment' => 'endpoint_msms',
            'path' => '/sms/send',
            'payload' => json_encode(['to' => '+35794000000', 'message' => 'Stress test single message.',])
        ]);

        Test::create([
            'name' => "Estimate Campaign",
            'type' => "post",
            'environment' => 'endpoint_msms',
            'path' => '/sms/estimate',
            'payload' => json_encode([
                'message' => 'Stress test campaign.',
                'to' => [
                    '+35799000000',
                    '35799000001'
                ]
            ])
        ]);

        Test::create([
            'name' => "Send Campaign",
            'type' => "post",
            'environment' => 'endpoint_msms',
            'path' => '/sms/send',
            'payload' => json_encode([
                'message' => 'Stress test campaign.',
                'to' => [
                    '+35799000000',
                    '35799000001'
                ]
            ])
        ]);

        Test::create([
            'name' => "Estimate Personalized Campaign",
            'type' => "post",
            'environment' => 'endpoint_msms',
            'path' => '/sms/estimate',
            'payload' => json_encode([
                'messages' => [
                    0 => [
                        'to' => '+35794000000',
                        'message' => 'Stress test personalized campaign'
                    ],
                    1 => [
                        'to' => '+35794000001',
                        'message' => 'Stress test personalized campaign'
                    ],
                ]
            ])
        ]);

        Test::create([
            'name' => "Send Personalized Campaign",
            'type' => "post",
            'environment' => 'endpoint_msms',
            'path' => '/sms/send',
            'payload' => json_encode([
                'messages' => [
                    0 => [
                        'to' => '+3579000000',
                        'message' => 'Stress test personalized campaign'
                    ],
                    1 => [
                        'to' => '+35794000001',
                        'message' => 'Stress test personalized campaign'
                    ],
                ]
            ])
        ]);
    }
}
