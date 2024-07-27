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
        Schema::create('user_analysis', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('current_badge')->default('Newbie');
            $table->integer('current_streak')->default(0);
            $table->integer('longest_streak')->default(0);
            $table->date('most_productive_day')->nullable();
            $table->decimal('status_ranking')->nullable();
            $table->text('description')->nullable();
            $table->text('qualification')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_analysis');
    }
};
