<?php

namespace App\Http\Controllers;

use App\Models\MyTodoList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShowDataDB extends Controller
{
    public function index(){
        $users = DB::table('users')->get();
        return view('table_users', compact('users'));
    }
}
