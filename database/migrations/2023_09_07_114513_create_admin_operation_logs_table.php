<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('admin_operation_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('admin_user_id');
            $table->string('method');
            $table->string('ip');
            $table->text('params');
            $table->text('response_params')->nullable();
            $table->string('browser');
            $table->integer('status_code');
            $table->string('url');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_operation_logs');
    }
};
