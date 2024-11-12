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
    public function index()
    {
        // Retrieve all billing records (no user restriction for pages)
        $billings = Billing::all(); // pages can see all billings

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
