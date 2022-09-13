<?php

use App\Http\Controllers\ShortUrlController;
use App\Http\Controllers\StatsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

#region Short URL
Route::post('/short-urls', [ShortUrlController::class, 'store'])->name('api.short-urls.store');
Route::delete('/short-urls/{shortUrl:code}', [ShortUrlController::class, 'destroy'])->name('api.short-urls.destroy');
#endregion

#region Statistics
Route::get('short-urls/{shortUrl:code}/stats/last-visit', [StatsController::class, 'lastVisit'])->name('api.short-urls.stats.last-visit');
#endregion
