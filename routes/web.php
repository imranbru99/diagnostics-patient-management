<?php

use App\Http\Controllers\PatientPortalController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Frontend
Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/tests', [PublicController::class, 'testCatalog'])->name('public.tests');
Route::get('/tests/{slug}', [PublicController::class, 'testDetail'])->name('public.test-detail');
Route::get('/book-appointment', [PublicController::class, 'bookAppointmentForm'])->name('public.book-appointment');
Route::post('/book-appointment', [PublicController::class, 'storeAppointment'])->name('public.store-appointment');
Route::get('/book-appointment/confirmed/{number}', [PublicController::class, 'bookingConfirmation'])->name('public.booking-confirmation');
Route::get('/tv-queue', [PublicController::class, 'tvQueue'])->name('public.tv-queue');
Route::get('/verify-report/{hash}', [PublicController::class, 'verifyReport'])->name('public.verify-report');

// AI Package Recommender API
Route::post('/api/recommend-packages', [PublicController::class, 'recommendPackagesApi'])->name('api.recommend-packages');

// Patient Portal
Route::prefix('portal')->name('portal.')->group(function () {
    Route::get('/login', [PatientPortalController::class, 'showLogin'])->name('login');
    Route::post('/login', [PatientPortalController::class, 'login'])->name('login.submit');
    Route::post('/logout', [PatientPortalController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [PatientPortalController::class, 'dashboard'])->name('dashboard');
    Route::post('/chat', [PatientPortalController::class, 'chatAboutReport'])->name('chat');
});

// Direct universal slug report access
Route::get('/report/{slug}', [PatientPortalController::class, 'viewReport'])->name('report.view');
Route::get('/report/{slug}/pdf', [PatientPortalController::class, 'downloadPdf'])->name('report.pdf');
