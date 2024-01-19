<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function users()
    {
        $users = DB::table('users')->get();
        return $users;
    }

    public function userLogin(Request $request)
    {
        $data = $request->all();
        $userEmail = DB::table('users')->where('email', $data['email'])->first();
        $userPassword = DB::table('users')->where('password', $data['password'])->first();

        if ($userEmail && $userPassword) {
            $response = [
                'status' => true,
                'data' => $userEmail,
            ];
            return response()->json($response, 201);
        } else {
            $response = [
                'status' => false,
                'error' => 'User is not found, invalid username or password',
            ];
            sleep(2);
            return response()->json($response, 201);
        }
    }

    public function createUser(Request $request)
    {
        $data = $request->all();
        $user = DB::table('users')->where('email', $data['email'])->first();

        if ($user) {
            $response = [
                'status' => false,
                'error' => 'User exists',
            ];
            return response()->json($response, 201);
        } else {
            DB::table('users')->insert([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
            ]);

            $newUser = DB::table('users')->where('email', $data['email'])->first();

            $response = [
                'status' => true,
                'data' => $newUser,
            ];
            return response()->json($response, 201);
        }

    }
}
