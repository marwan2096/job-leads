<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class JobApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
 public function index()
{
    $query = JobApplication::latest();

    if (auth()->user()->role == 'company_owner') {
        $query->whereHas('jobVacancy', function ($query) {
            $query->where('company_id', auth()->user()->company->id);
        });
    }

    $jobApplication = $query->with('resume', 'jobVacancy')->paginate(10)->onEachSide(1);

    foreach ($jobApplication as $application) {
        if ($application->resume && $application->resume->fileUri) {
            if (str_contains($application->resume->fileUri, 'http')) {
                $path = parse_url($application->resume->fileUri, PHP_URL_PATH);
                $path = ltrim($path, '/');
                $application->resume->fileUri = $path;
            }

            try {
                $application->resume->tempUrl = Storage::disk('cloud')->temporaryUrl(
                    $application->resume->fileUri,
                    now()->addMinutes(10)
                );
            } catch (\Exception $e) {
                $application->resume->tempUrl = null;
            }
        }
    }

    return view('appl.index', compact('jobApplication'));

    }

    /**
     * Show the form for creating a new resource.
     */


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(JobApplication $application)
    {

        return view('appl.show', compact('application'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JobApplication $application)
    {
          return view('appl.edit', compact('application'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JobApplication $application)
    {

            $request->validate([
                'status' => 'required|in:pending,accepted,rejected',
            ]);


        $application->update($request->only(['status']));
        return redirect()->route('application.index')->with('success', 'Application updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JobApplication $application)
    {
        $application->delete();
        return redirect()->route('application.index')->with('success', 'Application deleted successfully.');
    }


      public function archiveView()
    {
        $applications = JobApplication::onlyTrashed()->latest()->paginate(10)->onEachSide(1);
        return view('appl.archive', compact('applications'));
    }




    public function restore($id)
    {
        $application = JobApplication::onlyTrashed()->findOrFail($id);
        $application->restore();
        return redirect()->route('application.index')->with('success', 'Application restored successfully.');
    }


    public function forceDelete($id)
{
    $application = JobApplication::onlyTrashed()->findOrFail($id);


    $application->forceDelete();
    return redirect()->route('application.archiveView')->with('success', 'Application deleted permanently.');
}
}
