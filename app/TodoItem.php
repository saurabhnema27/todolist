<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\TodoList;

class TodoItem extends Model
{

    protected $fillable = ['title','desc','todolist_id','creator','updated_at','created_at','status'];

    public function users()
    {
        return $this->belongsTo('App\User','creator');
    }

    public function TodoList()
    {
        return $this->belongsTo('App\TodoList','todolist_id');
    }

    public function createTodoItem($request,$user)
    {
        $todoItem = new TodoItem;

        // checking the todolist
        $todolist = TodoList::where('name','My List')->first();
        $checkRequestTodoListId = TodoList::find($request->todolist_id);
        if(!$checkRequestTodoListId)
        {
            $request->todolist_id = $todolist->id;
        }

        if($todolist == NULL)
        {

            $todolistobj = new TodoList;
            $request['name'] = 'My List';
            $newTodoList = $todolistobj->addTodoList($request,$user);
        }

        $todoItem->title = $request->title;
        $todoItem->desc = $request->desc;
        if($request->start_date)
        {
            $todoItem->start_date = $request->start_date;
        }
        if($request->due_date)
        {
            $todoItem->due_date = $request->due_date;
        }
        if($request->assign_to)
        {
            $this->assign_to();
        }
        $todoItem->todolist_id = $request->todolist_id;
        $todoItem->creator = $user->id;

        $todoItem->save();
        return $todoItem;
        
    }

}
