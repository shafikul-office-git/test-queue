<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

class FileUploadController extends Controller
{
    public function index()
    {
        return Inertia::render('Test');
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:30720', // 30MB max size
        ]);

        if ($request->file('file')) {
            $filePath = $request->file('file')->store('uploads', 'public'); // Save to `storage/app/public/uploads`

            return response()->json([
                'message' => 'File uploaded successfully',
                'path' => asset('storage/' . $filePath), // Public URL
            ], 200);
        }

        return response()->json(['message' => 'File upload failed'], 400);
    }
}
