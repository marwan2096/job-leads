<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PhpParser\Builder\Use_;
use Illuminate\Validation\Rules\Password;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companies = Company::latest()->paginate()->onEachSide(1);
        return view('company.index', compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Show the form to create a new company



    return view('company.create');


    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCompanyRequest $request)
    {
        // Validate the request data
        $companyData = $request->validated();
      $ownerData= $request->validate([
            'owner_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Password::defaults()],

       ]);
      $owner= User::create([
        'name'     => $ownerData['owner_name'],

        'email'    => $ownerData['email'],
        'password' => Hash::make($ownerData['password']),
        'role'     => 'company_owner',
       ]);

        // Create the new company
        Company::create([
        'owner_id' => $owner->id,
        'name'     => $companyData['name'],
        'address'  => $companyData['address'] ?? null,
        'industry' => $companyData['industry'] ?? null,
        'website'  => $companyData['website'] ?? null,
    ]);
        // Redirect to the company index page with a success message
        return redirect()->route('company.index')->with('success', 'Company created successfully.');
    }

    /**
     * Display the specified resource.
     */
        public function show(string $id = null)
    {
        $company = $this->getCompany($id);

        return view('company.show', compact('company'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id = null)
    {
        $company = $this->getCompany($id);


        return view('company.edit', compact('company'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update( UpdateCompanyRequest $request, Company $company)
    {
          $company->update($request->validated());
        return redirect()->route('company.index')->with('success', 'Company updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
          $company->delete();
        return redirect()->route('company.index')->with('success', 'Company deleted successfully.');
    }



    public function archiveView()
    {
        $companies = Company::onlyTrashed()->latest()->paginate(10)->onEachSide(1);
        return view('company.archive', compact('companies'));
    }




    public function restore($id)
    {
        $company = Company::onlyTrashed()->findOrFail($id);
        $company->restore();
        return redirect()->route('company.index')->with('success', 'Company restored successfully.');
    }


    public function forceDelete($id)
{
    $company = Company::onlyTrashed()->findOrFail($id);

    abort_if($company->jobVacancies()->exists(), 403, 'Company has related vacancies.');

    $company->forceDelete();
    return redirect()->route('company.archiveView')->with('success', 'Company deleted permanently.');
}

private function getCompany(string $id = null)
    {
        if ($id) {
            return Company::findOrFail($id);
        }
        return Company::where('owner_id', auth()->user()->id)->first();
    }
}
