<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\LostFound;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LostFoundController extends Controller
{
    public function create()
    {
        return view('lostfound.report');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'status' => 'required|in:lost,found',
            'item_name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'required|min:20',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'date' => 'required|date',
            'time' => 'nullable|date_format:H:i',
        ]);

        $data = [
            'item_name'     => $validated['item_name'],
            'item_category' => $validated['category'] ?? null,
            'item_status'   => $validated['status'],
            'location'      => $validated['location'],
            'description'   => $validated['description'],
            'reported_by'   => Auth::id(),
            'image'         => null,
        ];

        // Handle image upload with better error handling
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            try {
                $file = $request->file('image');
                
                // Validate file
                if (!$file->getMimeType() || !str_starts_with($file->getMimeType(), 'image/')) {
                    Log::warning('Invalid file type attempted: ' . $file->getMimeType());
                } else {
                    // Create unique filename with timestamp
                    $timestamp = now()->timestamp;
                    $randomId = bin2hex(random_bytes(5));
                    $extension = $file->getClientOriginalExtension();
                    $filename = "{$timestamp}_{$randomId}.{$extension}";
                    
                    // Store file
                    $path = $file->storeAs('lost-found', $filename, 'public');
                    
                    if ($path) {
                        $data['image'] = $path;
                        Log::info('Image uploaded successfully: ' . $path);
                    }
                }
            } catch (\Exception $e) {
                Log::error('Image upload error: ' . $e->getMessage());
            }
        }

        // Handle date and time - IMPORTANT: Ensure proper format
        $date = $validated['date']; // Format: YYYY-MM-DD
        $time = !empty($validated['time']) ? $validated['time'] : '00:00'; // Format: HH:MM
        
        // Combine properly: YYYY-MM-DD HH:MM:SS
        $eventDateTime = $date . ' ' . $time . ':00';
        
        Log::info('Event datetime being saved: ' . $eventDateTime);
        
        $data['event_datetime'] = $eventDateTime;

        try {
            LostFound::create($data);
            
            return redirect()
                ->route('dashboard')
                ->with('success', 'Lost & Found report submitted successfully');
        } catch (\Exception $e) {
            Log::error('Failed to create lost & found record: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->with('error', 'Failed to submit report. Please try again.');
        }
    }
}