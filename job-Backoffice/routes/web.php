<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\JobCategoryController;
use App\Http\Controllers\JobVacancyController;

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });
// shared routes
Route::middleware(['auth', 'role:admin,company_owner'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');


});
Route::middleware(['auth', 'role:admin,company_owner'])->group(function () {


// Job Vacancies

Route::get('vacancy/archive', [JobVacancyController::class, 'archiveView'])->name('vacancy.archiveView');
        Route::resource('vacancy', JobVacancyController::class);
        Route::put('vacancy/{id}/restore', [JobVacancyController::class, 'restore'])->name('vacancy.restore');
Route::delete('vacancy/{id}/force-delete', [JobVacancyController::class, 'forceDelete'])->name('vacancy.forceDelete');
// Job Applications
Route::get('application/archive', [JobApplicationController::class, 'archiveView'])->name('application.archiveView');
        Route::resource('application', JobApplicationController::class);
              Route::put('application/{id}/restore', [JobApplicationController::class, 'restore'])->name('application.restore');
Route::delete('application/{id}/force-delete', [JobApplicationController::class, 'forceDelete'])->name('application.forceDelete');


});
// company owner routes

Route::middleware(['auth', 'role:company_owner'])->group(function () {

 Route::get('my-company', [CompanyController::class, 'show'])->name('my-company.show');
 Route::get('my-company/edit', [CompanyController::class, 'edit'])->name('my-company.edit');
Route::put('my-company', [CompanyController::class, 'update'])->name('my-company.update');


});



// admin routes
Route::middleware(['auth', 'role:admin'])->group(function () {

   // Users
    Route::get('user/archive', [UserController::class, 'archiveView'])->name('user.archiveView');
    Route::resource('user', UserController::class);
    Route::put('user/{id}/restore', [UserController::class, 'restore'])->name('user.restore');
Route::delete('user/{id}/force-delete', [UserController::class, 'forceDelete'])->name('user.forceDelete');



    // Job Categories
     Route::get('category/archive', [JobCategoryController::class, 'archiveView'])->name('category.archiveView');
       Route::resource('category', JobCategoryController::class);
Route::put('category/{id}/restore', [JobCategoryController::class, 'restore'])->name('category.restore');
Route::delete('category/{id}/force-delete', [JobCategoryController::class, 'forceDelete'])->name('category.forceDelete');


// Companies
        Route::get('company/archive', [CompanyController::class, 'archiveView'])->name('company.archiveView');
          Route::resource('company', CompanyController::class);

Route::put('company/{id}/restore', [CompanyController::class, 'restore'])->name('company.restore');
Route::delete('company/{id}/force-delete', [CompanyController::class, 'forceDelete'])->name('company.forceDelete');

});

// web.php
Route::get('/unauthorized', function () {
    return view('unauthorized');
})->name('unauthorized');

require __DIR__.'/auth.php';
