<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Cache;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class Post implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * User token
     *
     * @var string
     */
    protected $token;

    /**
     * The test_id to perform
     *
     * @var array
     */
    protected $test_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $token, int $test_id)
    {
        $this->token = $token;
        $this->test_id = $test_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $test = Cache::get('rec_' . $this->test_id);
        if ($test['name'] == 'Import contacts') {
            return $this->importContacts($test);
        }
        $endpoint = auth()->user()->{$test['environment']} . $test['path'];
        while ($test['is_running']) {
            $start = microtime(true);
            $response = Http::withToken($this->token)
                ->post($endpoint, json_decode($test['payload'], true));
            $test = Cache::get('rec_' . $this->test_id);
            $test['status'] = $response->status();
            $test['hits'] += 1;
            $duration = microtime(true) - $start;
            $test['duration'] = round($duration, 2);

            Cache::put('rec_' . $test['id'], $test, now()->addDay());
            usleep(1000000 / $test['rate']);
        }
    }

    protected function importContacts(array $test)
    {
        $endpoint = auth()->user()->{$test['environment']} . $test['path'];
        $response = Http::withToken($this->token)->get(auth()->user()->{$test['environment']} . '/v1/people/lists?name=Stress');
        $payload = [
            'user_lists' => json_encode([$response->json()['data'][0]]),
            'upload_method' => true
        ];

        $phones = [];
        $phone = 35794000000;
        for ($i = 0; $i < 1000; $i++) {
            $phones[] = ['phone' => '+' . $phone];
            $phone++;
        }
        $payload['contacts'] = json_encode($phones);
        $test['payload'] = $payload;

        $start = microtime(true);
        $response = Http::withToken($this->token)
            ->post($endpoint, $test['payload']);
        $test = Cache::get('rec_' . $this->test_id);
        $test['status'] = $response->status();
        $test['hits'] += 1;
        $duration = microtime(true) - $start;
        $test['duration'] = round($duration, 2);

        Cache::put('rec_' . $test['id'], $test, now()->addDay());

        return $test;
    }

}
