<?php

namespace App\Http\Controllers\Api;

use Illuminate\Validation\ValidationException;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;

class CompanyController extends Controller
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
            // Validate the incoming data
            $validatedData = $request->validate([
                'companyName' => 'required|string|max:255',
                'contactNo' => 'required|string|max:20',
                'companyEmail' => 'required|email|max:255',
                'foundationDate' => 'required|date',
                'services' => 'required|array',
                'services.*' => 'required|string', // Assuming each service is a string
                'location' => 'required|string',
            ]);

            // Create the company
            Company::create([
                'company_name' => $validatedData['companyName'],
                'contact_no' => $validatedData['contactNo'],
                'company_email' => $validatedData['companyEmail'],
                'company_foundation_date' => $validatedData['foundationDate'],
                'services' => json_encode($validatedData['services']),
                'company_location' => $validatedData['location'],
            ]);

            return response()->json([
                'message' => 'Company created successfully',
            ], 201);
        } catch (ValidationException $e) {
            // Log validation errors
            \Log::error('Validation Error:', ['errors' => $e->errors()]);
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            // Log unexpected errors
            \Log::error('Unexpected Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'message' => 'An unexpected error occurred.',
            ], 500);
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
