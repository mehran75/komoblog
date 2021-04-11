<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostLabelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_labels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id');
            $table->foreignId('label_id');
            $table->timestamps();

            $table->foreign('post_id')
                ->references('id')->on('posts')
                ->onDelete('cascade');;
            $table->foreign('label_id')
                ->references('id')->on('labels')
                ->onDelete('cascade');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('post_labels');
    }
}
