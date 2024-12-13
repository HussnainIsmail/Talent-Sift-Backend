<?php

namespace App\Http\Controllers\Api;

use App\Models\Job;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\Company;
class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $authjobs = Job::where('user_id', $user->id)->with(['jobTypes', 'workLocations'])->get();
        return response()->json([
            'authjobs' => $authjobs->map(function ($authjobs) {
                return [
                    'id' => $authjobs->id,
                    'jobtitle' => $authjobs->jobtitle,
                    'description' => $authjobs->description,
                    'jobTypes' => $authjobs->jobTypes,
                    'workLocations' => $authjobs->workLocations,
                ];
            }),
        ]);
    }


    // Store

    public function store(Request $request)
    {
        try {
            $user = auth()->user();

            // Check if the authenticated user is registered in the company table
            $company = Company::where('user_id', $user->id)->first();

            // If the user is not registered as a company, return an error message
            if (!$company) {
                return response()->json([
                    'message' => 'Please first register as a company.',
                ], 403);
            }

            // $user = $request->user();
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
                'minSalary' => 'required|numeric|min:0',
                'maxSalary' => 'required|numeric|min:0|gte:minSalary',
                'jobLevel' => 'nullable|array',
                'jobLevel.*' => 'string',
            ]);

            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('job_images', 'public');
            }

            // Create job record
            $job = Job::create([
                'user_id' => $user->id,
                'jobtitle' => $validated['jobtitle'],
                'email' => $validated['email'],
                'description' => $validated['description'],
                'subscribe' => $validated['subscribe'] ?? 0,
                'image' => $imagePath,
                'minSalary' => $validated['minSalary'] ?? null,
                'maxSalary' => $validated['maxSalary'] ?? null,
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

            // Save job levels
            if (!empty($validated['jobLevel'])) {
                foreach ($validated['jobLevel'] as $level) {
                    $job->jobLevels()->create(['level' => $level]);
                }
            }

            return response()->json([
                'message' => 'Job created successfully',
                'job' => $job->load('jobTypes', 'workLocations', 'jobLevels'),
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function edit($id)
    {
        try {
            // Find the job by ID along with its related data (jobTypes, jobLevels, workLocations)
            $job = Job::with(['jobTypes', 'jobLevels', 'workLocations'])->findOrFail($id);
            // Return the job with its related data
            return response()->json([
                'message' => 'Job found successfully',
                'job' => $job,
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // If job with the given ID is not found, return an error response
            return response()->json([
                'message' => 'Job not found',
                'error' => 'The job with the provided ID does not exist',
            ], 404);
        }
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $job = Job::find($id);

            if (!$job) {
                return response()->json([
                    'message' => 'Job not found',
                ], 404);
            }

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
                'minSalary' => 'required|numeric|min:0',
                'maxSalary' => 'required|numeric|min:0|gte:minSalary',
                'jobLevel' => 'nullable|array',
                'jobLevel.*' => 'string',
            ]);

            // Handle image upload (optional)
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('job_images', 'public');
                $job->image = $imagePath;
            }

            // Update job record
            $job->update([
                'jobtitle' => $validated['jobtitle'],
                'email' => $validated['email'],
                'description' => $validated['description'],
                'subscribe' => $validated['subscribe'] ?? 0,
                'minSalary' => $validated['minSalary'],
                'maxSalary' => $validated['maxSalary'],
            ]);

            // Update job types, work locations, and job levels if provided
            if (!empty($validated['jobType'])) {
                $job->jobTypes()->delete(); // Delete old job types
                foreach ($validated['jobType'] as $type) {
                    $job->jobTypes()->create(['type' => $type]);
                }
            }

            if (!empty($validated['workLocation'])) {
                $job->workLocations()->delete(); // Delete old work locations
                foreach ($validated['workLocation'] as $location) {
                    $job->workLocations()->create(['location' => $location]);
                }
            }

            if (!empty($validated['jobLevel'])) {
                $job->jobLevels()->delete(); // Delete old job levels
                foreach ($validated['jobLevel'] as $level) {
                    $job->jobLevels()->create(['level' => $level]);
                }
            }

            return response()->json([
                'message' => 'Job updated successfully',
                'job' => $job->load('jobTypes', 'workLocations', 'jobLevels'),
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $job = Job::find($id);

        if (!$job) {
            return response()->json([
                'message' => 'Job not found',
            ], 404);
        }

        // Delete related job types, work locations, and job levels before deleting the job
        $job->jobTypes()->delete();
        $job->workLocations()->delete();
        $job->jobLevels()->delete();

        $job->delete();

        return response()->json([
            'message' => 'Job deleted successfully',
        ]);
    }
}
