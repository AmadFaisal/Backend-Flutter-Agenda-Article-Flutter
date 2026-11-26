<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TripTypeController;
use App\Http\Controllers\Api\HolidayPlanController;

/*
|--------------------------------------------------------------------------
| Public Routes (tidak perlu token)
|--------------------------------------------------------------------------
*/
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| Protected Routes (butuh Bearer Token Sanctum)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::get('/user',   function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);

    // ====== UAS: Tabel 1 (Induk) ======
    Route::apiResource('trip-types', TripTypeController::class);

    // ====== UAS: Tabel 2 (Anak, relasi ke trip-types) ======
    Route::apiResource('holiday-plans', HolidayPlanController::class);

    // (opsional, dari project lama — boleh dihapus kalau tidak dipakai)
    // Route::apiResource('articles', \App\Http\Controllers\Api\ArticleController::class);
});

/*
|--------------------------------------------------------------------------
| Media (opsional — hanya kalau masih pakai upload file)
|--------------------------------------------------------------------------
*/
Route::get('/media/{path}', function (string $path) {
    abort_unless(str_starts_with($path, 'articles/'), 404);
    abort_unless(Storage::disk('public')->exists($path), 404);
    return Storage::disk('public')->response($path);
})->where('path', '.*');
