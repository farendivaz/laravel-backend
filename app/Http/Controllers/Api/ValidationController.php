<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Validation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ValidationController extends Controller
{

    public function index()
    {
        $validations = Validation::with(['society', 'user', 'jobCategory'])->get();

        return response()->json([
            'success' => true,
            'data' => $validations
        ]);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'job_category_id' => 'required|exists:job_categories,id',
            'work_experience' => 'required|string',
            'job_position' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $validation = Validation::create([
            'society_id' => auth()->id(),
            'job_category_id' => $request->job_category_id,
            'work_experience' => $request->work_experience,
            'job_position' => $request->job_position,
            'status' => 'pending'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Validation request submitted successfully',
            'data' => $validation
        ]);
    }

    public function update(Request $request, Validation $validation)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:accepted,declined',
            'validator_notes' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = auth()->guard('api')->user();

        $validation->update([
            'status' => $request->status,
            'validator_notes' => $request->validator_notes,
            'user_id' => $user->id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Validation updated successfully',
            'data' => $validation
        ]);
    }
}
