<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
Schema::create('api_calls', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->foreignId('api_token_id')->constrained('api_tokens')->onDelete('cascade');
    $table->string('endpoint');
    $table->integer('credits_used');
    $table->json('request_data')->nullable();
    $table->json('response_data')->nullable();
    $table->string('ip_address');
    $table->timestamp('called_at');
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_calls');
    }
};
