<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\JobApplication;
use App\Models\JobCategory;
use App\Models\JobVacancy;
use App\Models\Resume;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name'              => 'Admin User',
                'password'          => Hash::make('password'),
                'role'              => 'admin',
                'email_verified_at' => now(),
            ]
        );

        $jobData = json_decode(file_get_contents(database_path('data/job_data.json')), true);


        // 2. Job Categories
        foreach ($jobData['jobCategories'] as $category) {
            JobCategory::firstOrCreate(['name' => $category]);
        }

        // 3. Companies + Owners
        foreach ($jobData['companies'] as $company) {
            $owner = User::firstOrCreate(
                ['email' => strtolower(str_replace(' ', '', $company['name'])) . '@example.com'],
                [
                    'name'              => $company['name'] . ' Owner',
                    'password'          => Hash::make('password'),
                    'role'              => 'company_owner',
                    'email_verified_at' => now(),
                ]
            );

            Company::firstOrCreate(
                ['name' => $company['name']],
                [
                    'address'  => $company['address'],
                    'industry' => $company['industry'],
                    'website'  => $company['website'],
                    'owner_id' => $owner->id,
                ]
            );
        }

        // 4. Job Vacancies
        foreach ($jobData['jobVacancies'] as $job) {
            $company  = Company::where('name', $job['company'])->firstOrFail();
            $category = JobCategory::where('name', $job['category'])->firstOrFail();



            JobVacancy::firstOrCreate(
                [
                    'title'      => $job['title'],
                    'company_id' => $company->id,
                ],
                [
                    'description'     => $job['description'],
                    'location'        => $job['location'],
                    'type'            => $job['type'],
                    'salary'          => $job['salary'],

                    'category_id'     => $category->id,
                ]
            );
        }
 $jobApplications = json_decode(file_get_contents(database_path('data/job_applications.json')), true);
        //job applications

        foreach ($jobApplications['jobApplications']  as $application) {
            $user = User::firstOrCreate(
                ['email' => fake()->unique()->safeEmail()],
                [
                    'name'              => fake()->name(),
                    'password'          => Hash::make('password'),
                    'role'              => 'job_seeker',
                    'email_verified_at' => now(),
                ]
            );
            // randomly assign a job vacancy to the application
            $jobVacancy = JobVacancy::inRandomOrder()->first();

           $resume= Resume::Create(

                [
                    'user_id' => $user->id,
                    'filename' => $application['resume']['filename'],
                    'fileUri' => $application['resume']['fileUri'],
                    'contactDetails' => $application['resume']['contactDetails'],
                    'summary'   => $application['resume']['summary'],
                    'skills'    => $application['resume']['skills'],
                    'experience' => $application['resume']['experience'],
                    'education' => $application['resume']['education'],

                ]
            );

            JobApplication::firstOrCreate(
                [
                    'user_id'        => $user->id,
                    'job_vacancy_id' => $jobVacancy->id,
                    'resume_id'      => $resume->id,
                ],
                [
                    'status'             => $application['status'],
                    'aiGeneratedScore'   => $application['aiGeneratedScore'],
                    'aiGeneratedFeedback' => $application['aiGeneratedFeedback'],
                ]
            );
        }
    }
}
