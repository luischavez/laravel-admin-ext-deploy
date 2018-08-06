<?php

namespace Luischavez\Admin\Deploy;

use Illuminate\Database\Eloquent\Model;

class DeployModel extends Model
{

    /**
     * Settings constructor.
     *
     * @param array $attributes
     */
    public function __construct($attributes = [])
    {
        parent::__construct($attributes);

        $this->setConnection(config('admin.database.connection') ?: config('database.default'));
        $this->setTable(config('admin.extensions.deploy.table', 'admin_deploy'));
    }
}
