<?php

namespace Luischavez\Admin\Deploy;

use Illuminate\Queue\SerializesModels;

class DeployEvent
{

    use SerializesModels;

    public $deploy;

    public function __construct(DeployModel $deploy)
    {
        $this->deploy = $deploy;
    }
}
