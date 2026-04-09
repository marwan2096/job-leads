<?php

namespace App\Http\Controllers;

use App\Models\JobVacancy;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $query = JobVacancy::query();
        // if ($request->has('search')) {
        //     $search = $request->input('search');
        //     $query->where(function ($q) use ($search) {
        //         $q->where('title', 'like', "%{$search}%")
        //             ->orWhere('location', 'like', "%{$search}%")
        //             ->orWhereHas('company', function ($q2) use ($search) {
        //                 $q2->where('name', 'like', "%{$search}%");
        //             });
        //     });
        // }
   if ($request->has('search')) {
    $query->where('title', 'like', "%{$request->search}%");
}

if ($request->has('location')) {
    $query->where('location', 'like', "%{$request->location}%");
}

if ($request->has('company')) {
    $query->whereHas('company', fn($q) => $q->where('name', 'like', "%{$request->company}%"));
}
        if ($request->has('filter')) {
            $filter = $request->input('filter');
            $query->where('type', $filter);
        }

        $jobs = $query->with('company')->latest()->paginate(10)->withQueryString();
        return view('dashboard', compact('jobs'));
    }
}
