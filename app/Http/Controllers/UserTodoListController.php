<?php

namespace App\Http\Controllers;
use Auth;
use App\User;
use App\TodoList;
use App\TodoItem;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\AssignTodoItem;

use Illuminate\Http\Request;

/**
 * Note the validation can also be written in helper class to minise the code controller size
 */

class UserTodoListController extends Controller
{
    public function createTodoList(Request $request)
    {
        $validation = Validator::make($request->all(),[ 
            'name' => 'unique:todo_lists',
        ]);
    
        if($validation->fails())
        {
            return response()->json([
                'status' => 201, #this is used for only validations errors
                'errors' => $validation->errors()->all()
            ]);
        }

        $user = Auth::user();
        $todolist = new TodoList;

        if($request->name == NULL || empty($request->name))
        {
            $request->name = 'My List';
        }

        $find = TodoList::where('name',$request->name)->first();
        if($find)
        {
            return response()->json([
                'status' => 409,
                'message' => trans('message.defaultTodo')
            ]);
        }

        $createTodo = $todolist->addTodoList($request,$user);
        if($createTodo)
        {
            return response()->json([
                'status'=> 401, #todo related status code
                'message' => trans('message.TodoCreation')
            ]);
        }
    }

    public function addTodoItemsToList(Request $request)
    {
        $validation = Validator::make($request->all(),[ 
            'title' => 'required|string|min:2',
            'desc' => 'required|string|min:5',
        ]);
    
        if($validation->fails())
        {
            return response()->json([
                'status' => 201, #this is used for only validations errors
                'errors' => $validation->errors()->all()
            ]);
        }

        $creator = Auth::user();
        $todoItemobj = new TodoItem;
        $creatingItem = $todoItemobj->createTodoItem($request,$creator);
        if($creatingItem)
        {
            return response()->json([
                'status' => 402,
                'message' => trans('message.TodoItem'),
                'TodoItem' => $creatingItem            
            ]);
        }
    }

    public function changeStatusOfTodoItem(Request $request)
    {
        $validation = Validator::make($request->all(),[ 
            'status' => 'required',
            'todoitem_id' => 'required',
        ]);
    
        if($validation->fails())
        {
            return response()->json([
                'status' => 201, #this is used for only validations errors
                'errors' => $validation->errors()->all()
            ]);
        }

        $findtodoitem = TodoItem::find($request->todoitem_id);
        // print_r($findtodoitem);die();
        if($findtodoitem)
        {
            $findtodoitem->update([
                'status' => $request->status,
                'updated_at' => Carbon::now()
            ]);

            return response()->json([
                'status' => 403,
                'message' => trans('message.TodoStatus'),
                'TodoItem' => $findtodoitem
            ]);
        }

        return response()->json([
            'status' => 404,
            'message' => trans('message.TodoNotFound')
        ]);
    }

    public function getTodoListwithItems()
    {
        $user = Auth::user();
        $userdetails = User::where('id',$user->id)
        ->with('TodoList.TodoItems', 'assigntodo.todoitems')
        ->get();
        
        return response()->json([
            'status' => 405,
            'message' => trans('message.usertododetails'),
            'Details' => $userdetails
        ]);
    }

    public function assignTodoItemsToUser(Request $request)
    {
        $validation = Validator::make($request->all(),[ 
            'email' => 'required|email',
            'todoitem_id' => 'required',
            'comment' => 'required|max:1024'
        ]);
    
        if($validation->fails())
        {
            return response()->json([
                'status' => 201, #this is used for only validations errors
                'errors' => $validation->errors()->all()
            ]);
        }

        $getEmail = User::where('email',$request->email)->first();
        $user = Auth::user();

        if($user->id == $getEmail->id)
        {
            return response()->json([
                'status'=>407,
                'message' => trans("message.TodoAssignFail")
            ]);
        }

        if($getEmail)
        {
            $assignTodoObj = new AssignTodoItem;
            $saveDeatils = $assignTodoObj->AssignTodoItems($request,$user);
            if($saveDeatils)
            {
                $tododetails = TodoItem::where([
                    'creator' => $user->id,
                    'id' => $request->todoitem_id
                ])->update(['creator'=> $saveDeatils->assigned_to]);

                return response()->json([
                    'status'=> 406,
                    'message' => trans('message.todoasignsuccess'),
                ]);
            }
        }
        else
        {
            return response()->json([
                'status' => 404,
                'message' => 'User is not register to the system cant assign the todo item '
            ]);
        }
    }
}
