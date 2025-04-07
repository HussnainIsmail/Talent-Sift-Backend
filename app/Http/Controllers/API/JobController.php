<?php

namespace App\Http\Controllers\Api;

use App\Events\JobPosted; // Import the event
use App\Models\Job;
use App\Models\JobApplication;
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

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Retrieve all jobs and authenticated user's jobs
        $allJobs = Job::with(['jobTypes', 'workLocations'])->get();
        $authJobs = Job::where('user_id', $user->id)
            ->with(['jobTypes', 'workLocations'])
            ->get();

        // Format the data for response
        $formattedAllJobs = $allJobs->map(function ($job) {
            return [
                'id' => $job->id,
                'jobtitle' => $job->jobtitle,
                'description' => $job->description,
                'jobTypes' => $job->jobTypes,
                'workLocations' => $job->workLocations,
            ];
        });

        $formattedAuthJobs = $authJobs->map(function ($authJob) {
            return [
                'id' => $authJob->id,
                'jobtitle' => $authJob->jobtitle,
                'description' => $authJob->description,
                'jobTypes' => $authJob->jobTypes,
                'workLocations' => $authJob->workLocations,
            ];
        });

        return response()->json([
            'jobs' => $formattedAllJobs, // All jobs
            'authjobs' => $formattedAuthJobs, // Authenticated user's jobs
        ]);
    }

    // for show jobs in frontend
    public function show()
    {
        $jobs = Job::with(['jobTypes', 'jobLevels', 'workLocations', 'company'])->get();

        return response()->json([
            'jobs' => $jobs,
        ], 200);
    }


    // Store

    public function store(Request $request)
    {
        try {
            $user = auth()->user();
            // Get the company based on the company name from the request
            $company = Company::where('company_name', $request->company)->first();

            if (!$company) {
                return response()->json([
                    'message' => 'Please register a valid company first.',
                ], 403);
            }

            // Validate the job request data
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

            // Handle image upload if present
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('job_images', 'public');
            }

            // Create the job record with the company_id
            $job = Job::create([
                'user_id' => $user->id,
                'company_id' => $company->id,  // Use the company_id found by company name
                'jobtitle' => $validated['jobtitle'],
                'email' => $validated['email'],
                'description' => $validated['description'],
                'subscribe' => $validated['subscribe'] ?? 0,
                'image' => $imagePath,
                'minSalary' => $validated['minSalary'],
                'maxSalary' => $validated['maxSalary'],
            ]);

            // Save job types if provided
            if (!empty($validated['jobType'])) {
                foreach ($validated['jobType'] as $type) {
                    $job->jobTypes()->create(['type' => $type]);
                }
            }

            // Save work locations if provided
            if (!empty($validated['workLocation'])) {
                foreach ($validated['workLocation'] as $location) {
                    $job->workLocations()->create(['location' => $location]);
                }
            }

            // Save job levels if provided
            if (!empty($validated['jobLevel'])) {
                foreach ($validated['jobLevel'] as $level) {
                    $job->jobLevels()->create(['level' => $level]);
                }
            }

            // Broadcast the job posting event
            broadcast(new JobPosted($job));

            return response()->json([
                'message' => 'Job created successfully',
                'job' => $job->load('jobTypes', 'workLocations', 'jobLevels'),
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred',
                'error' => $e->getMessage(),
            ], 500);
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
