<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TodoList extends Model
{
    // relationship mapping
    public function users()
    {
        return $this->belongsTo('App/User','user_id');
    }

    public function TodoItems()
    {
        return $this->hasMany('App\TodoItem','todolist_id');
    }

    protected $fillable = ['owner','name'];

    public function addTodoList($request,$user)
    {
        $todoList = new TodoList;
        $todoList::create([
            'name' => $request->name,
            'owner' => $user->id
        ]);

        return $todoList;
    }
}
