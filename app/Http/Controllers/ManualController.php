<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ManualController extends Controller
{
    /**
     * Display the manual page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Return the view for the static manual page
        return view('pages.uscAdminManual');
    }
}
