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
    public function index($job)
    {
        if (!$job) {
            return response()->json([
                'message' => 'Job ID is required.',
            ], 400);
        }

        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Fetch job applications
        $jobApplications = JobApplication::where('job_id', $job)->get();

        $formattedApplications = $jobApplications->map(function ($application) {
            $cvText = null;
            $cvImagePath = null;

            // Full path to the PDF (assuming stored in `storage/app/public/cvs`)
            $pdfPath = storage_path('app/public/' . $application->cv_path);

            if (file_exists($pdfPath)) {
                try {
                    // Convert PDF to image
                    $imagePath = storage_path('app/public/temp/page_' . $application->id . '.jpg');
                    $pdf = new Pdf($pdfPath);
                    $pdf->setOutputFormat('jpg')->setResolution(200)->saveImage($imagePath);

                    // Apply OCR on the image
                    $cvText = (new TesseractOCR($imagePath))->lang('eng')->run();

                    // Optional: Store image path if you want to send it too
                    $cvImagePath = asset('storage/temp/page_' . $application->id . '.jpg');
                } catch (\Exception $e) {
                    $cvText = 'Failed to process CV: ' . $e->getMessage();
                }
            } else {
                $cvText = 'CV not found at ' . $pdfPath;
            }

            return [
                'id' => $application->id,
                'first_name' => $application->first_name,
                'last_name' => $application->last_name,
                'email' => $application->email,
                'contact_no' => $application->contact_no,
                'cv_path' => $application->cv_path,
                'cv_image_url' => $cvImagePath,
                'cv_text' => $cvText,
            ];
        });

        return response()->json([
            'job_applications' => $formattedApplications,
            'job_id' => $job,
        ]);
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
