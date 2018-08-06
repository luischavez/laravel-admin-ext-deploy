<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminDeployTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $connection = config('admin.database.connection') ?: config('database.default');
        $table = config('admin.extensions.deploy.table', 'admin_deploy');

        Schema::connection($connection)->create($table, function (Blueprint $table) {
            $table->increments('id');
            $table->text('status')->nullable();
            $table->boolean('finished')->default(false);
            $table->boolean('success')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $connection = config('admin.database.connection') ?: config('database.default');
        $table = config('admin.extensions.deploy.table', 'admin_deploy');

        Schema::connection($connection)->dropIfExists($table);
    }
}