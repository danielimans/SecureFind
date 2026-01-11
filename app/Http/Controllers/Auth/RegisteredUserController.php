<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    // Display the registration view

    public function create(): View
    {
        return view('auth.register');
    }

    // Handle an incoming registration request

    public function store(Request $request): RedirectResponse
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'],
            'password_confirmation' => ['required', 'string', 'min:8'],
            'role' => ['required', 'in:student,staff'],
        ], [
            'name.required' => 'Full name is required',
            'name.regex' => 'Name can only contain letters and spaces',
            'email.required' => 'Email is required',
            'email.email' => 'Please enter a valid email address',
            'email.unique' => 'This email is already registered',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 8 characters',
            'password.regex' => 'Password must contain uppercase, lowercase, and number',
            'password.confirmed' => 'Passwords do not match',
            'role.required' => 'Please select a user role',
            'role.in' => 'Invalid user role selected',
        ]);

        // Create the user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        // Fire the Registered event
        event(new Registered($user));

        // Redirect to login page with success message
        return redirect()->route('login')->with('success', 'Registration successful! Please log in with your credentials.');
    }
}