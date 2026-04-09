<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Http\Requests\CreateVacancyRequest;
use App\Http\Requests\UpdateVacancyRequest;
use App\Models\Company;
use App\Models\JobCategory;
use App\Models\JobVacancy;
use Illuminate\Http\Request;

class JobVacancyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()


    {
 // Active
        $query = JobVacancy::latest();

        if(auth()->user()->role == 'company_owner'){
            $query->where('company_id', auth()->user()->company->id);
        }
        $vacancies = $query->paginate(10)->onEachSide(1);
        return view('vacancy.index', compact('vacancies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()

    {
        $categories = JobCategory::all();
       if (auth()->user()->role === 'admin') {
        $companies = Company::all();
    } else {
        $companies = Company::where('owner_id', auth()->user()->id)->get();
    }

    return view('vacancy.create', compact('companies', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateVacancyRequest $request)
    {
        $data = $request->validated();
        JobVacancy::create($data);


        return redirect()->route('vacancy.index')->with('success', 'Vacancy created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(JobVacancy $vacancy)
    {
            $jobapplications = $vacancy->applications()->with('user', 'jobVacancy')->latest()->paginate()->onEachSide(1);
    {
    //      $jobVacancies = $company->jobVacancies()->latest()->paginate()->onEachSide(1);
    //    $jobapplications = $company->jobApplications()->with('user', 'jobVacancy')->latest()->paginate()->onEachSide(1);
        return view('vacancy.show', compact( 'vacancy', 'jobapplications'));
    }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JobVacancy $vacancy)
    {
        $categories = JobCategory::all();
        $companies = Company::all();
        return view('vacancy.edit', compact('vacancy', 'categories', 'companies'));
    }

    /**
     * Update the specified resource in storage.
     */
   public function update(UpdateVacancyRequest $request, JobVacancy $vacancy)
{
    $vacancy->update($request->validated());
    return redirect()->route('vacancy.index')->with('success', 'Vacancy updated successfully.');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JobVacancy $vacancy)
    {
        $vacancy->delete();
        return redirect()->route('vacancy.index')->with('success', 'Vacancy deleted successfully.');
    }

  public function archiveView()
    {
        $vacancies = JobVacancy::onlyTrashed()->latest()->paginate(10)->onEachSide(1);
        return view('vacancy.archive', compact('vacancies'));
    }




    public function restore($id)
    {
        $vacancy = JobVacancy::onlyTrashed()->findOrFail($id);
        $vacancy->restore();
        return redirect()->route('vacancy.index')->with('success', 'Vacancy restored successfully.');
    }


    public function forceDelete($id)
{
    $vacancy = JobVacancy::onlyTrashed()->findOrFail($id);



    $vacancy->forceDelete();
    return redirect()->route('vacancy.archiveView')->with('success', 'Vacancy deleted permanently.');
}










}
