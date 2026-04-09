<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApplyRequest;
use App\Models\JobApplication;
use App\Models\JobVacancy;
use App\Models\Resume;
use App\Services\ResumeExtractService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use OpenAI\Laravel\Facades\OpenAI;

class VacancyController extends Controller
{
    protected $resumeExtractService;

    public function __construct(ResumeExtractService $resumeExtractService)
    {
        $this->resumeExtractService = $resumeExtractService;
    }

    public function show($id)
    {
        $jobVacancy = JobVacancy::with('company')->findOrFail($id);
         $jobVacancy->increment('view_count');
        return view('vacancy.show', compact('jobVacancy'));
    }

    public function apply($id)
    {
        $jobVacancy = JobVacancy::with('company')->findOrFail($id);
        $resumes = auth()->user()->resumes()->latest()->get();
        return view('vacancy.apply', compact('jobVacancy', 'resumes'));
    }

    public function store(ApplyRequest $request, $id)
    {
        try {


    Log::info('All request data:', $request->all());
    Log::info('Resume option:', ['value' => $request->resume_option]);
    Log::info('Existing resume ID:', ['value' => $request->existing_resume_id]);
            $jobVacancy = JobVacancy::findOrFail($id);
            $extractedInfo = null;
            $resumeId = null;

            if ($request->resume_option === 'new_resume') {
                $file = $request->file('resume_file');
                $extension = $file->getClientOriginalExtension();
                $originalName = $file->getClientOriginalName();
                $filename = 'resume_' . time() . '_' . $originalName;

                $path = $file->storeAs('resumes', $filename, ['disk' => 'cloud', 'visibility' => 'public']);

                $fileUrl = config('filesystems.disks.cloud.url') . '/' . $path;

                // Extract resume information
                $extractedInfo = $this->resumeExtractService->extractResume($fileUrl);

                // Ensure all values are strings
                $extractedInfo = array_map(function($value) {
                    if (is_array($value)) {
                        return json_encode($value);
                    }
                    return (string) $value;
                }, $extractedInfo);

                // Create contact details array
                $contactDetails = [
                    'email' => auth()->user()->email ?? '',
                    'name' => auth()->user()->name ?? ''
                ];

                $resume = Resume::create([
                    'user_id' => auth()->id(),
                    'filename' => $filename,
                    'fileUri' => $fileUrl,
                    'contactDetails' => json_encode($contactDetails),
                    'summary' => $extractedInfo['summary'] ?? '',
                    'skills' => $extractedInfo['skills'] ?? '',
                    'experience' => $extractedInfo['experience'] ?? '',
                    'education' => $extractedInfo['education'] ?? '',
                ]);

                $resumeId = $resume->id;
                Log::info('Resume created successfully', ['resume_id' => $resumeId]);

           } else {
    $resumeId = $request->resume_option; // fix #1

    $existingResume = Resume::findOrFail($resumeId);
    $extractedInfo = [
        'summary'    => $existingResume->summary ?? '',
        'skills'     => is_string($existingResume->skills)
                            ? json_decode($existingResume->skills, true) ?? $existingResume->skills
                            : $existingResume->skills,
        'experience' => is_string($existingResume->experience)
                            ? json_decode($existingResume->experience, true) ?? $existingResume->experience
                            : $existingResume->experience,
        'education'  => is_string($existingResume->education)
                            ? json_decode($existingResume->education, true) ?? $existingResume->education
                            : $existingResume->education,
    ];
}

            // 🔥 ANALYZE THE RESUME AGAINST THE JOB (MOVED HERE - BEFORE RETURN)
            $evaluation = $this->resumeExtractService->analyzeResume($jobVacancy, $extractedInfo);

            // Create job application with AI evaluation
            $application = JobApplication::create([
                'status' => 'pending',
                'job_vacancy_id' => $id,
                'resume_id' => $resumeId,
                'user_id' => auth()->id(),
                'aiGeneratedScore' => $evaluation['aiGeneratedScore'],
                'aiGeneratedFeedback' => $evaluation['aiGeneratedFeedback'],
            ]);

            Log::info('Job application created successfully', [
                'application_id' => $application->id,
                'ai_score' => $evaluation['aiGeneratedScore']
            ]);

            // Redirect to application result page
            return redirect()->route('job-applications.index', $application->id)
                ->with('success', 'Application submitted successfully!')
                ->with('ai_score', $evaluation['aiGeneratedScore'])
                ->with('ai_feedback', $evaluation['aiGeneratedFeedback']);

        } catch (\Exception $e) {
            Log::error('Error in VacancyController store method', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
}
