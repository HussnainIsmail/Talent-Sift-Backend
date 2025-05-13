<?php

namespace App\Http\Controllers\API;

use Spatie\PdfToImage\Pdf;
use thiagoalessio\TesseractOCR\TesseractOCR;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
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
    try {
        // Validate the request data
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:job_applications,email',
            'contact_no' => 'required|string|max:15',
            'cv' => 'required|file|mimes:pdf,doc,docx,mp4,txt|max:4096',  
            'job_id' => 'required|exists:jobs,id',
            'company_id' => 'required|exists:companies,id',
        ]);

        // Define the directory path in public storage
        $cvDirectory = 'cvs';

        // Check if the directory exists, create it if it doesn't
        if (!Storage::disk('public')->exists($cvDirectory)) {
            Storage::disk('public')->makeDirectory($cvDirectory);
        }

        // Get the original file extension and store the file with its original extension
        $fileExtension = $request->file('cv')->getClientOriginalExtension();
        $cvPath = $request->file('cv')->storeAs($cvDirectory, uniqid() . '.' . $fileExtension, 'public');

        // Check if the job exists
        $job = Job::find($validatedData['job_id']);
        if (!$job) {
            return response()->json([
                'message' => 'Job not found.',
            ], 404);
        }

        // Create the job application
        $jobApplication = JobApplication::create([
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'email' => $validatedData['email'],
            'contact_no' => $validatedData['contact_no'],
            'cv_path' => $cvPath,
            'job_id' => $validatedData['job_id'],
            'company_id' => $validatedData['company_id'],
        ]);

        // Return a success response
        return response()->json([
            'message' => 'Job application submitted successfully!',
            'job_application' => $jobApplication,
        ], 201);

    } catch (ValidationException $e) {
        // If validation fails, return a custom response with the validation errors
        return response()->json([
            'message' => 'Validation failed.',
            'errors' => $e->errors(),  // Display the validation errors
        ], 422);

    } catch (\Exception $e) {
        // Catch any other exceptions and return a general error message
        return response()->json([
            'message' => 'Something went wrong. Please try again later.',
            'error' => $e->getMessage(),
        ], 500);
    }
}



    public function extractTextFromPdf()
    {
        $pdfPath = public_path('pdf/sample.pdf');
        $imagePath = public_path('pdf/page.jpg');

        // Convert first page of PDF to image
        $pdf = new Pdf($pdfPath);
        $pdf->setOutputFormat('jpg')->setResolution(300);
        $pdf->saveImage($imagePath);

        // Apply OCR
        $text = (new TesseractOCR($imagePath))
            ->lang('eng') // Set language if needed
            ->run();

        // Output or return the result
        return response()->json(['text' => $text]);
    }
}
