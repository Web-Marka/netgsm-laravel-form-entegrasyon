<?php
use App\Http\Controllers\SmsController;
use Illuminate\Support\Facades\Route;

Route::post('/reservation', [SmsController::class, 'Reservation'])->name('reservation');

require __DIR__.'/auth.php';
