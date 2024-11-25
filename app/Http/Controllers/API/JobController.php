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
        $jobs = Job::with('jobTypes', 'workLocations')->get();
        return response()->json([
            'jobs' => $jobs,
        ], 200);
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

            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('job_images', 'public');
            }

            // Create job record
            $job = Job::create([
                'jobtitle' => $validated['jobtitle'],
                'email' => $validated['email'],
                'description' => $validated['description'],
                'subscribe' => $validated['subscribe'] ?? 0,
                'image' => $imagePath,
            ]);

            // Save job types
            if (!empty($validated['jobType'])) {
                foreach ($validated['jobType'] as $type) {
                    $job->jobTypes()->create(['type' => $type]);
                }
            }

            // Save work locations
            if (!empty($validated['workLocation'])) {
                foreach ($validated['workLocation'] as $location) {
                    $job->workLocations()->create(['location' => $location]);
                }
            }

            return response()->json([
                'message' => 'Job created successfully',
                'job' => $job->load('jobTypes', 'workLocations'),
            ], 201);
        } catch (ValidationException $e) {
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
