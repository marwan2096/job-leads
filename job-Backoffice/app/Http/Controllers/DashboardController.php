<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use App\Models\JobVacancy;
use App\Models\User;



class DashboardController extends Controller
{
    public function index()
    {
        if (auth()->user()->role === 'admin') {
              $analytics = $this->adminDashboard();
        } elseif (auth()->user()->role === 'company_owner') {
           $analytics = $this->companyDashboard();
        }

        return view('dashboard.index', compact('analytics'));
    }


    public function adminDashboard()
    {
          $activeUsers = User::
            where('role', '=', 'job_seeker')->count();
        $totalJobs = JobVacancy::whereNull('deleted_at')->count();
        $totalApplications = JobApplication::whereNull('deleted_at')->count();

        $mostAppliedJobs = JobVacancy::with('company')->withCount('applications')
            ->orderBy('applications_count', 'desc')
            ->take(5)
            ->get();


        //conversion rates
        $conversionRates = JobVacancy::withCount('applications')
            ->having('applications_count', '>', 0)
            ->orderBy('applications_count', 'desc')
            ->take(5)

            ->get()

            ->map(function ($job) {

                $job->conversion_rate = $job->view_count > 0 ? round(($job->applications_count / $job->view_count) * 100, 2) : 0;
                return $job;
            });




        $analytics = [
            'active_users' => $activeUsers,
            'total_jobs' => $totalJobs,
            'total_applications' => $totalApplications,
            'most_applied_jobs' => $mostAppliedJobs,
            'conversion_rates' => $conversionRates,

        ];
        return $analytics;
    }

   public function companyDashboard()
    {
        $company = auth()->user()->company;
        $vacancyIds = $company->jobVacancies()->pluck('id');

$activeUsers =
    User::where('role', 'job_seeker')
    ->whereHas('jobApplications', function ($query) use ($vacancyIds) {
        $query->whereIn('job_vacancy_id', $vacancyIds);
    })
    ->count();


    // $activeUsers = User::where('last_login_at', '>=', now()->subDays(30))
    // ->where('role', '=', 'job_seeker')
    // ->whereHas('jobApplications', function ($query) use ($company) {
    //     $query->whereHas('jobVacancy', function ($query) use ($company) {
    //         $query->where('company_id', $company->id);
    //     });
    // })
    // ->count();

        $totalJobs = JobVacancy::whereNull('deleted_at')->where('company_id', $company->id)->count();
        $totalApplications = JobApplication::whereHas('jobVacancy', function ($query) use ($company) {
    $query->where('company_id', $company->id);
})->count();

        $mostAppliedJobs = JobVacancy::with('company')->withCount('applications')
        ->where('company_id', $company->id)
            ->orderBy('applications_count', 'desc')
            ->take(5)
            ->get();


        //conversion rates
        $conversionRates = JobVacancy::withCount('applications')
         ->where('company_id', $company->id)
            ->having('applications_count', '>', 0)
            ->orderBy('applications_count', 'desc')
            ->take(5)

            ->get()

            ->map(function ($job) {

                $job->conversion_rate = $job->view_count > 0 ? round(($job->applications_count / $job->view_count) * 100, 2) : 0;
                return $job;
            });




        $analytics = [
            'active_users' => $activeUsers,
            'total_jobs' => $totalJobs,
            'total_applications' => $totalApplications,
            'most_applied_jobs' => $mostAppliedJobs,
            'conversion_rates' => $conversionRates,

        ];
        return $analytics;
    }







}
