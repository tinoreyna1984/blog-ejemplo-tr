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
        Schema::create('user_post', function (Blueprint $table) {
            //$table->id();
            $table->primary(['post_id','user_id']);
            $table->bigInteger('user_id')->unsigned();
            $table->uuid('post_id');
            //$table->string('note'); // para pivotar
            $table->timestamps();

            $table->foreign('post_id')
                ->references('post_id')
                ->on('posts')
                ->onDelete('cascade')
                ->onUpdate('cascade');
             $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_post');
    }
};
