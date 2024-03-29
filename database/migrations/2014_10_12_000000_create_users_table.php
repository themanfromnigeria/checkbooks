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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['reader', 'author', 'admin'])->default('reader');
            // $table->json('role')->nullable()->comment('0: user, 1: author, 2: admin');
            $table->integer('borrowing_points')->default(0); // Track borrowing points
            $table->enum('status', ['active', 'inactive'])->default('active')->comment('active, inactive');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
