<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function TodoList()
    {
        return $this->hasMany('App\TodoList','owner');
    }

    public function TodoItem()
    {
        return $this->hasMany('App\TodoItem','creator');
    }

    public function assigntodo()
    {
        return $this->hasMany('App\AssignTodoItem','assigned_to');
    }

    public function RegisterUser($request)
    {
        $user = new User;
        $userCreation = $user::create([
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        return $userCreation;
    }
}
