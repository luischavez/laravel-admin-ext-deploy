<?php

namespace Luischavez\Admin\Deploy;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Event;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class DeployTask implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $deploy;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(DeployModel $deploy = null)
    {
        $this->deploy = $deploy ?: DeployModel::create([]);
        $this->updateDeployStatus('creating deploy task');
    }

    private function updateDeployStatus($status, $finished = false, $error = false)
    {
        $this->deploy->status .= $status;
        $this->deploy->finished = $finished;
        $this->deploy->error = $error;
        $this->deploy->save();
    }

    private function runCommand($command)
    {
        $program = new Process($command, base_path(), null, null, null);

        $this->updateDeployStatus('<p>' . $command);

        $program->run();

        if($program->isSuccessful()){
            $this->updateDeployStatus('...success</p>');
        } else {
            $this->updateDeployStatus('...error</p>');
            throw new ProcessFailedException($program);
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $debug = config('app.debug', false);
            $branch = config('admin.extensions.deploy.branch', 'master');

            $this->updateDeployStatus('<p>initialize deploy</p>');

            $this->runCommand('php artisan down');

            if ($debug) $this->runCommand('git reset --hard');

            $this->runCommand('git pull origin ' . $branch);
            $this->runCommand('composer install ' . ($debug ? '' : ' --no-dev'));
            $this->runCommand('php artisan migrate ' . ($debug ? ':fresh' : ''));

            if ($debug) $this->runCommand('php artisan db:seed');

            $this->runCommand('php artisan up');

            $this->updateDeployStatus('<p>finished deploy</p>', true, false);

            Event::fire(new DeployEvent($this->deploy));
        } catch (Exception $ex) {
            $this->updateDeployStatus('<p>' . $ex->getMessage() . '</p>', true, true);
        }
    }
}
