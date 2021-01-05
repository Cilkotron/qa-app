<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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
Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('question', 'QuestionController')->except('show');
Route::resource('question.answer', 'AnswerController')->except(['index', 'create', 'show']);
// Route::post('/question/{question}/answer', 'AnswerController@store');
Route::get('/question/{slug}', 'QuestionController@show')->name('question.show');
Route::post('/answer/{answer}/accept', 'AcceptAnswerController')->name('answer.accept');
Route::post('/question/{question}/favorites', 'FavoriteController@store')->name('question.favorite');
Route::delete('/question/{question}/favorites', 'FavoriteController@destroy')->name('question.unfavorite');

Route::post('/question/{question}/vote', 'VoteQuestionController');








