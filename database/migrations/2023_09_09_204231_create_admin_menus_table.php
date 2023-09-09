<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_menus', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->integer('pid')->default(0);
            $table->integer('type')->comment('1-菜单 2-按钮');
            $table->string('path')->nullable();
            $table->string('name')->nullable();
            $table->string('component')->nullable();
            $table->string('icon')->nullable();
            $table->integer('ignoreCache')->nullable();
            $table->integer('hideInMenu')->nullable();
            $table->string('permission')->nullable();
            $table->integer('sort');
            $table->integer('status')->default(1);
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
        Schema::dropIfExists('admin_menus');
    }
}
