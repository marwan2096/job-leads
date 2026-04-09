<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::latest()->paginate(10)->onEachSide(1);
        return view('user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

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
    public function show(User $user)
    {
            $users = $user->jobApplications()->with('jobVacancy')->latest()->paginate(10)->onEachSide(1);
        return view('user.show', compact('user', 'users'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('user.edit', compact('user'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, User $user)
    {

         $data = $request->validated();

    if (blank($data['password'])) {
        unset($data['password']);
    } else {
        $data['password'] = bcrypt($data['password']);
    }

    $user->update($data);
        return redirect()->route('user.index')->with('success', 'User updated successfully.');

    }


    public function destroy(User $user) {
        $user->delete();
        return redirect()->route('user.index')->with('success', 'User deleted successfully.');
    }


        public function archiveView()
    {
        $users = User::onlyTrashed()->latest()->paginate(10)->onEachSide(1);
        return view('user.archive', compact('users'));
    }




    public function restore($id)
    {
        $users = User::onlyTrashed()->findOrFail($id);
        $users->restore();
        return redirect()->route('user.index')->with('success', 'User restored successfully.');
    }


    public function forceDelete($id)
{
    $users = User::onlyTrashed()->findOrFail($id);


    $users->forceDelete();
    return redirect()->route('user.archiveView')->with('success', 'User deleted permanently.');
}

    /**
     * Remove the specified resource from storage.
     */
}
