<?php

use App\Http\Controllers\ClientController;
use Illuminate\Http\Request;
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
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');


// for get clients
Route::get('dashboard/clients',function(Request $request) {
    return view('clients',[
        'clients' => $request->user()->clients,
        'tokens' => $request->user()->tokens,
    ]);
})->middleware('auth')->name('dashboard.clients');



require __DIR__.'/auth.php';


