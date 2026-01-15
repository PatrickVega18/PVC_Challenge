<?php

use App\Http\Controllers\CreditReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CreditReportController::class, 'index'])->name('home');
Route::get('/export-report', [CreditReportController::class, 'export'])->name('report.export');
Route::get('/download-report/{filename}', [CreditReportController::class, 'download'])->name('report.download');
