<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Society;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SocietyController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'id_card_number' => 'required|string|unique:societies',
            'password' => 'required|string|min:8',
            'born_date' => 'required|date',
            'gender' => 'required|in:male,female',
            'address' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $society = Society::create([
            'name' => $request->name,
            'id_card_number' => $request->id_card_number,
            'password' => Hash::make($request->password),
            'born_date' => $request->born_date,
            'gender' => $request->gender,
            'address' => $request->address,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Society registered successfully',
            'data' => $society
        ]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_card_number' => 'required|string',
            'password' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $credentials = $request->only('id_card_number', 'password');

        // Use society-api guard as defined in your auth.php
        if (!$token = auth()->guard('society')->attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid ID Card Number or Password'
            ], 401);
        }

        return response()->json([
            'success' => true,
            'user' => auth()->guard('society')->user(),
            'token' => $token,
        ], 200);
    }
}
