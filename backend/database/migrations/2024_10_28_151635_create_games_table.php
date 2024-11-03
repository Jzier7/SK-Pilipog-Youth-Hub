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
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('event_id');
            $table->unsignedBigInteger('team1_id');
            $table->integer('team1_score')->nullable();
            $table->unsignedBigInteger('team2_id');
            $table->integer('team2_score')->nullable();
            $table->unsignedBigInteger('winner')->nullable();
            $table->unsignedBigInteger('loser')->nullable();
            $table->string('status')->default('pending');
            $table->dateTime('date');
            $table->timestamps();

            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->foreign('team1_id')->references('id')->on('teams')->onDelete('cascade');
            $table->foreign('team2_id')->references('id')->on('teams')->onDelete('cascade');
            $table->foreign('winner')->references('id')->on('teams')->onDelete('cascade');
            $table->foreign('loser')->references('id')->on('teams')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};

