<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ListItemController extends Controller
{
    public function getListItem($id)
    {
        $task = DB::table('tasks')->where('user_id', '=', $id)->get();

        return $task;
    }

    public function addTask(Request $request)
    {
        $data = $request->all();
        DB::table('tasks')->insert([
            'user_id' => $data['user_id'],
            'task_name' => $data['task_name'],
            'status' => 'new',
        ]);
        $task = DB::table('tasks')->where('user_id', '=', $data['user_id'])->get();

        return $task;
    }

    public function removeTask(Request $request)
    {
        $data = $request->all();
        DB::table('tasks')->where('task_id', '=', $data['task_id'])->delete();
        $task = DB::table('tasks')->where('user_id', '=', $data['user_id'])->get();

        return $task;
    }

    public function updateTask(Request $request)
    {
        $data = $request->all();
        DB::table('tasks')
        ->where('task_id', '=', $data['task_id'])
        ->update([
            'task_name' => $data['task_name'],
            'status' => $data['status'],
        ]);
        $task = DB::table('tasks')->where('user_id', '=', $data['user_id'])->get();

        return $task;
    }
}
