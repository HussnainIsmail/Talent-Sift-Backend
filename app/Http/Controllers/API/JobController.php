<?php

namespace App\Http\Controllers\Api;

use App\Models\Job;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        try {
            // dd($request->all());
            // Validate request data
            $validated = $request->validate([
                'jobtitle' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'description' => 'required|string',
                'jobType' => 'nullable|array',
                'jobType.*' => 'string',
                'workLocation' => 'nullable|array',
                'workLocation.*' => 'string',
                'subscribe' => 'nullable|boolean',
                'image' => 'nullable|image|max:10240',
            ]);

            // Handle image upload and job creation
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('job_images', 'public');
            }

            // Create job record
            $job = Job::create([
                'jobtitle' => $validated['jobtitle'],
                'email' => $validated['email'],
                'description' => $validated['description'],
                'jobType' => $validated['jobType'] ?? [],
                'workLocation' => $validated['workLocation'] ?? [],
                'subscribe' => $validated['subscribe'] ?? 0,
                'image' => $imagePath,
            ]);

            return response()->json([
                'message' => 'Job created successfully',
                'job' => $job,
            ], 201);
        } catch (ValidationException $e) {
            // If validation fails, return custom error messages
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422); 
        }
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
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
