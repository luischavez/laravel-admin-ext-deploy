<?php

namespace Luischavez\Admin\Deploy;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

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

    private function runGitPull()
    {
        $branch = config('admin.extensions.deploy.branch', 'master');

        $git = new Process("git pull origin $branch");
        $git->setWorkingDirectory(base_path());

        $this->updateDeployStatus("<p>git pull origin $branch");

        $git->run();

        if($git->isSuccessful()){
            $this->updateDeployStatus('...success</p>');
        } else {
            $this->updateDeployStatus('...error</p>');
            throw new ProcessFailedException($git);
        }
    }

    private function runMigrate()
    {
        $migrate = new Process('php artisan migrate');
        $migrate->setWorkingDirectory(base_path());

        $this->updateDeployStatus('<p>php artisan migrate');

        $migrate->run();

        if($migrate->isSuccessful()){
            $this->updateDeployStatus('...success</p>');
        } else {
            $this->updateDeployStatus('...error</p>');
            throw new ProcessFailedException($migrate);
        }
    }

    private function runComposerInstall()
    {
        $install = new Process('composer install');
        $install->setWorkingDirectory(base_path());

        $this->updateDeployStatus('<p>composer install');

        $install->run();

        if($install->isSuccessful()){
            $this->updateDeployStatus('...success</p>');
        } else {
            $this->updateDeployStatus('...error</p>');
            throw new ProcessFailedException($install);
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
            $this->updateDeployStatus('<p>initialize deploy</p>');

            $this->runGitPull();
            $this->runComposerInstall();
            $this->runMigrate();

            $this->updateDeployStatus('<p>finished deploy</p>', true, false);
        } catch (Exception $ex) {
            $this->updateDeployStatus('<p>' . $ex->getMessage() . '</p>', true, true);
        }
    }
}
