<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('affiliates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('birthday');
            $table->string('cpf')->unique();
            $table->integer('phone');
            $table->string('address');
            $table->foreignId('city_id')->references('id')->on('cities');
            $table->foreignId('state_id')->references('id')->on('states');
            $table->string('code')->unique();
            $table->boolean('status')->default(true);
            $table->string('type')->default('affiliate');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliates');
    }
};
