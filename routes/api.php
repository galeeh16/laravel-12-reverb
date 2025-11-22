<?php 

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomAuthController;
use App\Http\Controllers\NotificationController;

Route::get('/notifications', [NotificationController::class, 'list']);
Route::post('/notifications', [NotificationController::class, 'store']);
Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);

Route::post('/custom/auth', [CustomAuthController::class, 'auth']);