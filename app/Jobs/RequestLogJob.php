<?php

namespace App\Jobs;

use App\Models\RequestLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RequestLogJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $log = new RequestLog([
            'url' => $this->data['url'],
            'method' => $this->data['method'],
            'ip' => $this->data['ip'],
            'params' => $this->data['params'],
            'response_params' => $this->data['response_params'],
            'user_id' => $this->data['user_id'],
        ]);
        $log->save();
    }
}
