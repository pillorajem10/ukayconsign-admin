<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Import the User model

class UserController extends Controller
{
    // Display a list of users
    public function index(Request $request)
    {
        $query = User::query(); // Start a query on the User model
    
        // Check if there's a search query for email
        if ($request->has('search') && $request->search != '') {
            $query->where('email', 'like', '%' . $request->search . '%'); // Filter by email
        }
    
        $users = $query->get(); // Fetch users based on the query
        return view('pages.usersList', compact('users')); // Pass the users to the view
    }

    // Show user details by ID
    public function show($id)
    {
        $user = User::findOrFail($id); // Find the user by ID or fail with a 404 error

        return view('pages.userDetails', compact('user')); // Pass the user to the view
    }
}
