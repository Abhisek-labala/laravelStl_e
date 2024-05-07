<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

class signupController extends Controller
{
    public function signup(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:regforms',
            'password' => 'required',
            'dob' => 'required|date',
            'phone' => 'required',
            'email' => 'required|email',
            'address' => 'required',
            'gender' => 'required',
            'country' => 'required',
            'state' => 'required',
            'hobbies' => 'array',
            'hobbies.*' => 'string',
            'fileToUpload' => 'required|image|mimes:jpg,jpeg,png,gif|max:5242880',
        ]);

        $hobbies = implode(',', $request->input('hobbies'));

        $file = $request->file('fileToUpload');
        $new_name = rand() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads'), $new_name);
        $phash = Hash::make($request->input('password'));

        $data = [
            'name' => $request->input('name'),
            'username' => $request->input('username'),
            'password' => $phash,
            'dob' => $request->input('dob'),
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
            'address' => $request->input('address'),
            'gender' => $request->input('gender'),
            'country' => $request->input('country'),
            'state' => $request->input('state'),
            'hobbies' => $hobbies,
            'image_url' => $new_name,
        ];

        try {
            DB::table('regforms')->insert($data);
            // Return JSON response with success message
            return response()->json(['message' => 'Account created successfully. Kindly login'], 200);
        } catch (\Exception $e) {
            // Return JSON response with error message
            return response()->json(['error' => 'Failed to create account. Please try again.'], 500);
        }
    }
}
