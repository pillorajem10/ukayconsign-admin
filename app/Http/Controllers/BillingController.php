<?php

namespace App\Http\Controllers;

use App\Models\Billing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BillingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Keep the authentication requirement for all methods
    }

    /**
     * Display a listing of all billings.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Start with the base query for billings, filtered by user role "user"
        $query = Billing::whereHas('user', function ($query) {
            $query->where('role', 'user'); // Assuming there's a 'role' column in the 'users' table
        });
    
        // Apply date range filter if dates are provided in the request
        if ($request->has('start_date') && $request->has('end_date')) {
            // Parse the start and end date to Carbon instances
            $startDate = \Carbon\Carbon::parse($request->start_date)->startOfDay();
            $endDate = \Carbon\Carbon::parse($request->end_date)->endOfDay();
    
            // Apply the date range filter to the query
            $query->whereBetween('bill_issued', [$startDate, $endDate]);
        }
    
        // Order the results by created_at and paginate the results with 10 records per page
        $billings = $query->orderBy('created_at', 'desc')->paginate(10);
    
        // Return the view and pass the $billings data to the Blade template
        return view('pages.billingList', compact('billings'));
    }
    
      

    /**
     * Display the breakdown of a specific billing.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Retrieve the billing record by its ID
        $billing = Billing::findOrFail($id);

        // Decode the JSON stored in 'billing_breakdown' to get the product details
        $billingBreakdown = json_decode($billing->billing_breakdown, true);

        // Return the view and pass the $billing and $billingBreakdown data
        return view('pages.billingBreakdown', compact('billing', 'billingBreakdown'));
    }

    /**
     * Show the form for uploading proof of payment.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showUploadProofOfPayment($id)
    {
        // Retrieve the billing record by its ID
        $billing = Billing::findOrFail($id);

        // Return the Blade view with the billing record
        return view('pages.uploadProofOfBilling', compact('billing'));
    }

    /**
     * Update the proof of payment and payment platform for a billing record.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updatePayment(Request $request, $id)
    {
        // Find the billing record by its ID
        $billing = Billing::findOrFail($id);

        // Check if the status is already "Paid"
        if ($billing->status === 'Paid') {
            return redirect()->route('billings.index')->with('error', 'This billing has already been marked as paid.');
        }

        // Update the status to "Paid"
        $billing->status = 'Paid';
        $billing->save(); // Save the updated status

        // Redirect back with a success message
        return redirect()->route('billings.index')->with('success', 'Billing has been marked as paid.');
    }
}
