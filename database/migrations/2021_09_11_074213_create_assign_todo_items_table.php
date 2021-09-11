<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssignTodoItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assign_todo_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('todoitem_id')->unsigned();
            $table->foreign('todoitem_id')->references('id')->on('todo_items')->onDelete('cascade');
            $table->integer('assigned_owner')->unsigned();
            $table->foreign('assigned_owner')->references('id')->on('users')->onDelete('cascade');
            $table->text('comments');
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
        Schema::dropIfExists('assign_todo_items');
    }
}
