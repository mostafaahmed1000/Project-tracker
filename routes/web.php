<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\AttachmentController;

Route::redirect("/", "/projects");
Route::redirect("/home", "/projects");

Route::middleware(['web','auth', 'verified'])->group(function () {
    Route::resource('projects', ProjectController::class);
    Route::post('projects/{id}/restore', [ProjectController::class, 'restore'])->name('projects.restore');
    Route::delete('projects/{id}/force-delete', [ProjectController::class, 'forceDelete'])->name('projects.force-delete');

    Route::resource('tasks', TaskController::class);
    Route::resource('attachments', AttachmentController::class);
  
    Route::get('/attachments/{attachment}/download', [AttachmentController::class, 'download'])
        ->name('attachments.download');

    
    Route::patch('/tasks/{task}/toggle-done', [TaskController::class, 'toggleDone'])
        ->name('tasks.toggle-done');
    Route::post('/tasks/{task}/restore', [TaskController::class, 'restore'])
    ->withTrashed() // allow binding soft deleted models
    ->name('tasks.restore');
    Route::delete('tasks/{id}/force-delete', [TaskController::class, 'forceDelete'])->name('tasks.force-delete');

});

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});

require __DIR__.'/auth.php';
