<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTodoItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('todo_items', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('desc');
            $table->date('start_date')->nullable();
            $table->date('due_date')->nullable();
            $table->enum('status',['Todo','In Progress','Done'])->default('Todo');
            $table->integer('todolist_id')->unsigned();
            $table->foreign('todolist_id')->references('id')->on('todo_lists')->onDelete('cascade');
            $table->integer('creator')->unsigned();
            $table->foreign('creator')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('todo_items');
    }
}
