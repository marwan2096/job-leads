<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;


class JobApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $jobApplications = $user->jobApplications()->with('jobVacancy.company', 'resume')->latest()->paginate(5);

        // Fix the resume URLs
        foreach ($jobApplications as $application) {
            if ($application->resume && $application->resume->fileUri) {
                // Check if the fileUri contains a full URL (has http)
                if (str_contains($application->resume->fileUri, 'http')) {
                    // Extract just the path from the full URL
                    $path = parse_url($application->resume->fileUri, PHP_URL_PATH);
                    // Remove leading slash if exists
                    $path = ltrim($path, '/');
                    $application->resume->fileUri = $path;
                }

                // Generate a temporary URL for the view
                try {
                    $application->resume->tempUrl = Storage::disk('cloud')->temporaryUrl(
                        $application->resume->fileUri,
                        now()->addMinutes(10)
                    );
                } catch (\Exception $e) {
                    $application->resume->tempUrl = null;
                    Log::error('Failed to generate resume URL', [
                        'resume_id' => $application->resume->id,
                        'fileUri' => $application->resume->fileUri,
                        'error' => $e->getMessage()
                    ]);
                }
            }
        }

        return view('job-applications.index', compact('jobApplications'));
    }
}
