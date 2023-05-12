<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable('姓名');
            $table->string('nickname')->nullable()->comment('昵称');
            $table->string('avatar')->nullable()->comment('头像');
            $table->string('oa_openid')->nullable()->comment('公众号openid');
            $table->string('phone')->nullable()->comment('手机号');
            $table->integer('role_id')->nullable()->comment('角色 1-普通用户 2-运营者');
            $table->integer('pid')->nullable()->comment('上级ID');
            $table->decimal('balance')->default(0)->comment('余额');
            $table->integer('status')->default(1)->comment('状态 1-正常 0-已禁用');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
