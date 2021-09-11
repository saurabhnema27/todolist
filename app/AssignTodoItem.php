<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class AssignTodoItem extends Model
{

    public function todoitems()
    {
        return $this->belongsTo('App\TodoItem','todoitem_id');
    }

    public function AssignTodoItems($request,$user)
    {
        $assign_to = User::where('email',$request->email)->first();
        $assignobj = new AssignTodoItem;
        $assignobj->comments = $request->comment;
        $assignobj->todoitem_id = $request->todoitem_id;
        $assignobj->assigned_owner = $user->id;
        $assignobj->assigned_to = $assign_to->id;

        $assignobj->save();

        return $assignobj;

    }
    
}
