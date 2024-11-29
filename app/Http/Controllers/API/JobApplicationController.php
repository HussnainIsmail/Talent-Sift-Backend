<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class JobApplicationController extends Controller
{
    public function index()
    {
        $jobApplications = JobApplication::all();

        return response()->json([
            'job_applications' => $jobApplications
        ]);
    }

    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:job_applications,email',
            'contact_no' => 'required|string|max:15',
            'cv' => 'required|file|mimes:pdf,doc,docx,mp4,txt|max:4096',  // Allow .mp4 and other types as needed
            'job_id' => 'required|exists:jobs,id',
        ]);

        // Define the directory path in public storage
        $cvDirectory = 'cvs';

        // Check if the directory exists, create it if it doesn't
        if (!Storage::disk('public')->exists($cvDirectory)) {
            Storage::disk('public')->makeDirectory($cvDirectory);
        }

        // Get the original file extension
        $fileExtension = $request->file('cv')->getClientOriginalExtension();

        // Store the file with its original extension
        $cvPath = $request->file('cv')->storeAs($cvDirectory, uniqid() . '.' . $fileExtension, 'public');

        $job = Job::find($validatedData['job_id']);
        if (!$job) {
            return response()->json([
                'message' => 'Job not found.',
            ], 404);
        }

        $jobApplication = JobApplication::create([
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'email' => $validatedData['email'],
            'contact_no' => $validatedData['contact_no'],
            'cv_path' => $cvPath, 
            'job_id' => $validatedData['job_id'], 
        ]);

        // Return a success response
        return response()->json([
            'message' => 'Job application submitted successfully!',
            'job_application' => $jobApplication,
        ], 201);
    }
}
