<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('recipe_id')->constrained('recipes');
            $table->integer('rating');
            $table->text('comment');
            $table->string('image_path')->nullable();
            $table->boolean('draft')->default(false);
            $table->timestamp('draft_updated_at')->nullable();
            $table->timestamps();

        /**
         * one user one published review for one recipe.
         */
        $table->unique(['user_id', 'recipe_id', 'draft']);
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
