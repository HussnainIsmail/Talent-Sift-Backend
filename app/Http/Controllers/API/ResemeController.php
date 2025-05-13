<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Mail;
use App\Models\JobApplication;
use App\Mail\InterviewMail;
use App\Http\Controllers\Controller;
use Spatie\PdfToImage\Pdf;
use thiagoalessio\TesseractOCR\TesseractOCR;
use Illuminate\Http\Request;

class ResemeController extends Controller
{
    public function index($jobId)
{
    try {
        if (!$jobId) {
            return response()->json([
                'message' => 'Job ID is required.',
            ], 400);
        }

        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Fetch job applications with their job and company relationships
        $jobApplications = JobApplication::with(['job', 'company'])
            ->where('job_id', $jobId)
            ->get();

        $formattedApplications = $jobApplications->map(function ($application) {
            // Generate the public URL for the CV file
            $cv_path = basename($application->cv_path);

            return [
                'id' => $application->id,
                'first_name' => $application->first_name,
                'last_name' => $application->last_name,
                'email' => $application->email,
                'contact_no' => $application->contact_no,
                'cv_path' => $cv_path,
                'job_title' => $application->job->title ?? null,
                'company_name' => $application->company->name ?? null,
                'created_at' => $application->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $application->updated_at->format('Y-m-d H:i:s'),
            ];
        });

        return response()->json([
            'job_applications' => $formattedApplications,
            'job_id' => $jobId,
            'count' => $jobApplications->count(),
        ]);

    } catch (\Exception $e) {
        \Log::error("Job applications index error: " . $e->getMessage());
        return response()->json([
            'message' => 'Failed to retrieve job applications',
            'error' => $e->getMessage()
        ], 500);
    }
}

    // show the detail for the email
    public function show($applicationId)
    {
        if (!$applicationId) {
            return response()->json([
                'message' => 'Application ID is required.',
            ], 400);
        }

        // Ensure the user is authenticated
        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Fetch the specific job application by its ID
        $application = JobApplication::with(['job', 'company'])->find($applicationId);

        if (!$application) {
            return response()->json(['message' => 'Application not found.'], 404);
        }

        // Return the full application data along with company and job details
        return response()->json([
            'job_application' => $application,
            'company' => $application->company,
            'job' => $application->job,
        ]);
    }


    public function sendInterviewEmail(Request $request, $applicationId)
    {
        // Validate request
        $validated = $request->validate([
            'interviewType' => 'required|string',
            'scheduledDate' => 'required|date',
        ]);

        // Find the job application
        $jobApplication = JobApplication::with('job', 'company')->find($applicationId);

        if (!$jobApplication) {
            return response()->json(['message' => 'Job application not found'], 404);
        }

        // Data for email
        $mailData = [
            'interviewType' => $validated['interviewType'],
            'scheduledDate' => $validated['scheduledDate'],
            'applicantName' => $jobApplication->name,
            'applicantEmail' => $jobApplication->email,
            'contactNo' => $jobApplication->contact_no,
            'jobTitle' => $jobApplication->job->jobtitle,
            'companyName' => $jobApplication->company->name,
            'companyLocation' => $jobApplication->company->location,
        ];

        try {
            // Send email
            Mail::to($jobApplication->email)->send(new InterviewMail($mailData));

            // Return success response
            return response()->json([
                'message' => 'Interview email has been sent successfully.',
                'data' => $mailData,
            ], 200);
        } catch (\Exception $e) {
            // Catch any exceptions and log them
            return response()->json([
                'message' => 'Failed to send email.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
