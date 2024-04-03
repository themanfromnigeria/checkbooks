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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('title')->unique();
            $table->string('edition')->nullable();
            $table->text('description')->nullable();
            $table->text('prologue')->nullable();
            $table->string('publisher')->nullable();
            $table->date('publication_date')->nullable();
            $table->string('isbn')->unique()->nullable();
            $table->decimal('price', 8, 2)->nullable();
            $table->tinyInteger('status')->default(0)->comment('0: active, 1: inactive');
            $table->enum('is_borrowed', ['borrowed', 'available'])->default('available')->comment('Borrowing status of the book'); //Track lending status. Unique by book
            // $table->boolean('is_borrowed')->default(false);
            $table->unsignedBigInteger('access_level_id')->nullable();
            $table->foreign('access_level_id')->references('id')->on('access_levels')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
