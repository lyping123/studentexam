<?php

namespace App\Http\Controllers;

use App\Models\student;
use App\Models\User;
use Illuminate\Http\Request;

class studentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $user=User::create([
            "name" => $request->name,
            "ic" => $request->ic,
            "password" => $request->password,
            "role"=>"student",
            "course_id" => $request->course_id,
        ]);
        // return response()->json($user, 201);
        // $student=student::create([
        //     "user_id" => $user->id,
        //     "course_id" => $request->course_id,
        //     "gender"=>$request->gender,
        // ]);
        return response()->json($user, 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
