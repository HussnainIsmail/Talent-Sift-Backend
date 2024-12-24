<?php

namespace App\Http\Controllers\Api;
use Illuminate\Support\Facades\Mail;
use App\Models\JobApplication;
use App\Models\Job;
use App\Mail\RegisterMail;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ResemeController extends Controller
{
    public function index($job)
    {
        if (!$job) {
            return response()->json([
                'message' => 'Job ID is required.',
            ], 400);
        }
        $jobApplications = JobApplication::where('job_id', $job)->get();

        return response()->json([
            'job_applications' => $jobApplications,
            'job_id' => $job,
        ]);
    }
    public function sendemail(Request $request, $id)
    {
        $validated = $request->validate([
            'interviewType' => 'required|string',
            'scheduledDate' => 'required|date',
        ]);
    
        $job = Job::find($id);
    
        if (!$job) {
            return response()->json(['message' => 'Job not found'], 404);
        }
    
        // Send email logic here
        // Mail::to($job->candidate_email)->send(new InterviewMail($validated));
        Mail::to($job->email)->send(new RegisterMail($job));

        return response()->json(['message' => 'Interview email sent successfully']);
    }
    
}
