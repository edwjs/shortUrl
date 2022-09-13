<?php

use App\Models\ShortUrl;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {

    // if (request()->isJson()) {
    //     return response()->json(['message' => 'Welcome to the Laravel Web API']);
    // }

    // return view('welcome');
    return ['Laravel' => app()->version()];
});

Route::get('{shortUrl:code}', function(ShortUrl $shortUrl) {
    $shortUrl->visits()->create([
        'ip_address' => request()->ip(),
        'user_agent' => request()->userAgent(),
    ]);

    return ['Laravel' => app()->version()];
});

require __DIR__.'/auth.php';
