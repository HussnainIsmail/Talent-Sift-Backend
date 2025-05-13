<?php

namespace App\Http\Controllers\Api;

use Illuminate\Validation\ValidationException;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $user = auth()->user();
            $company = $user->company;

            if (!$company) {
                return response()->json([
                    'data' => [], // return empty array instead of 404
                ], 200);
            }

            return response()->json([
                'data' => [$company], // wrap in array
            ], 200);
        } catch (\Exception $e) {
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
     * Show the form for creating a new resource.
     */
    public function create(Request $request) {

         try {
            $user = auth()->user();
    
            $company = $user->company; 
    
            if (!$company) {
                return response()->json([
                    'message' => 'No company found for this user.',
                ], 404);
            }    
            return response()->json([
                'data' => $company,
            ], 200);
        } catch (\Exception $e) {
            // Log unexpected errors
            \Log::error('Unexpected Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
    
            // Return a general error response
            return response()->json([
                'message' => 'An unexpected error occurred.',
            ], 500);
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = $request->user();
        $company = Company::where('user_id', $user->id)->first();

        $companyNameRule = 'required|string|max:255|unique:companies,company_name';
        $companyEmailRule = 'required|email|max:255|unique:companies,company_email';

        if ($company) {
            $companyNameRule .= ',' . $company->id;
            $companyEmailRule .= ',' . $company->id;
        }

        try {
            $validatedData = $request->validate([
                'companyName' => $companyNameRule,
                'contactNo' => 'required|string|max:20',
                'companyEmail' => $companyEmailRule,
                'foundationDate' => 'required|date',
                'services' => 'required|array',
                'services.*' => 'required|string',
                'location' => 'required|string|max:255',
            ]);

            $data = [
                'company_name' => $validatedData['companyName'],
                'contact_no' => $validatedData['contactNo'],
                'company_email' => $validatedData['companyEmail'],
                'company_foundation_date' => $validatedData['foundationDate'],
                'services' => json_encode($validatedData['services']),
                'company_location' => $validatedData['location'],
            ];

            if ($company) {
                $company->update($data);
                return response()->json(['message' => 'Company updated successfully']);
            } else {
                $data['user_id'] = $user->id;
                Company::create($data);
                return response()->json(['message' => 'Company created successfully'], 201);
            }
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Company store error', ['message' => $e->getMessage()]);
            return response()->json(['message' => 'Server error'], 500);
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
