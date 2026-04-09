<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\JobCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationData;

class JobCategoryController extends Controller
{
    /**
     * Constructor - apply middleware here
     */

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = JobCategory::latest()->paginate(10)->onEachSide(1);
        return view('category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateCategoryRequest $request)
    {
        $data = $request->validated();
        JobCategory::create($data);

        return redirect()->route('category.index')->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified resource.
     */

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JobCategory $category)
    {
        return view('category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, JobCategory $category)
    {
        $category->update($request->validated());
        return redirect()->route('category.index')->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JobCategory $category)
    {
        $category->delete();
        return redirect()->route('category.index')->with('success', 'Category deleted successfully.');
    }



    public function archiveView()
    {
        $categories = JobCategory::onlyTrashed()->latest()->paginate(10)->onEachSide(1);
        return view('category.archive', compact('categories'));
    }




    public function restore($id)
    {
        $category = JobCategory::onlyTrashed()->findOrFail($id);
        $category->restore();
        return redirect()->route('category.index')->with('success', 'Category restored successfully.');
    }


    public function forceDelete($id)
{
    $category = JobCategory::onlyTrashed()->findOrFail($id);

    abort_if($category->jobVacancies()->exists(), 403, 'Category has related vacancies.');

    $category->forceDelete();
    return redirect()->route('category.archiveView')->with('success', 'Category deleted permanently.');
}
}
