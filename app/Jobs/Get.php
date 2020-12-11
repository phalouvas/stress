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

class Get implements ShouldQueue
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
        $endpoint = auth()->user()->{$test['environment']} . $test['path'];

        if ($test['name'] == 'Hit Shortlink') {
            $endpoint = $this->hitShortlink($test);
        }

        while ($test['is_running']) {
            $start = microtime(true);
            $response = Http::withToken($this->token)
                ->get($endpoint);
            $test = Cache::get('rec_' . $this->test_id);
            $test['status'] = $response->status();
            $test['hits'] += 1;
            $duration = microtime(true) - $start;
            $test['duration'] = round($duration, 2);

            Cache::put('rec_' . $test['id'], $test, now()->addDay());
            usleep(1000000 / $test['rate']);
        }
    }

    protected function hitShortlink($test) {
        $response = Http::withToken($this->token)->get(auth()->user()->{$test['environment']} . $test['path']);
        return auth()->user()->{$test['environment']} . '/l/' . $response->json()['data'][0]['shortlink'];
    }
}
