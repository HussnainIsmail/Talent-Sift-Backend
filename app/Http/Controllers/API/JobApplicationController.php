<?php

namespace App\Http\Controllers\API;
use Illuminate\Support\Facades\Storage;
use App\Models\JobApplication;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Job;

class JobApplicationController extends Controller
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
         // Validate the request data
         $validatedData = $request->validate([
             'first_name' => 'required|string|max:255',
             'last_name' => 'required|string|max:255',
             'email' => 'required|email|unique:job_applications,email',
             'contact_no' => 'required|string|max:15',
             'cv' => 'required|file|mimes:pdf,doc,docx|max:2048',
             'job_id' => 'required|exists:jobs,id', 
         ]);
     
         // Define the directory path in public storage
         $cvDirectory = 'cvs';
     
         // Check if the directory exists, create it if it doesn't
         if (!Storage::disk('public')->exists($cvDirectory)) {
             Storage::disk('public')->makeDirectory($cvDirectory);
         }
     
         $cvPath = $request->file('cv')->store($cvDirectory, 'public');
     
         $job = Job::find($validatedData['job_id']);
         if (!$job) {
             return response()->json([
                 'message' => 'Job not found.',
             ], 404);
         }
     
         // Save the job application data in the database
         $jobApplication = JobApplication::create([
             'first_name' => $validatedData['first_name'],
             'last_name' => $validatedData['last_name'],
             'email' => $validatedData['email'],
             'contact_no' => $validatedData['contact_no'],
             'cv_path' => $cvPath, // Store the path to the uploaded CV
             'job_id' => $validatedData['job_id'], // Save the job_id foreign key
         ]);
     
         // Return a success response
         return response()->json([
             'message' => 'Job application submitted successfully!',
             'job_application' => $jobApplication,
         ], 201);
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
