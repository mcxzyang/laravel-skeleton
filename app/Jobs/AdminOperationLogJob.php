<?php

namespace App\Jobs;

use App\Models\AdminOperationLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Jenssegers\Agent\Agent;

class AdminOperationLogJob implements ShouldQueue
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
        $agent = new Agent();
        AdminOperationLog::query()->create([
            'admin_user_id' => $this->data['admin_user_id'],
            'method' => $this->data['method'],
            'ip' => $this->data['ip'],
            'params' => $this->data['params'],
            'response_params' => $this->data['response_params'],
            'browser' => $agent->browser(),
            'status_code' => $this->data['status_code'],
            'url' => $this->data['url'],
        ]);
    }
}
