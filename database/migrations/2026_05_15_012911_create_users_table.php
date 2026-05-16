<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('telepon', 20)->nullable();
            $table->enum('role', ['admin','staff','user'])->default('user');
            $table->string('password');
            $table->string('avatar')->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('google_id')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void {
        Schema::dropIfExists('users');
    }
};