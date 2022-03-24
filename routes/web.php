<?php

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
    $api_key = 'qIWJYZFtNSvR4r7lRhpINIQyGoLIwDlq0dQS';
    $api_secret = 'vL3oZhLQaL85sjVi9ZEhs4cQpG05Rnq2ECkl';
    $meeting_number = 87430267458;
    $role = 1;
    //Set the timezone to UTC
    date_default_timezone_set("UTC");
    $time = time() * 1000 - 30000;//time in milliseconds (or close enough)
    $data = base64_encode($api_key . $meeting_number . $time . $role);
    $hash = hash_hmac('sha256', $data, $api_secret, true);
    $_sig = $api_key . "." . $meeting_number . "." . $time . "." . $role . "." . base64_encode($hash);
    //return signature, url safe base64 encoded
    $signature =  rtrim(strtr(base64_encode($_sig), '+/', '-_'), '=');

    return view('welcome',[
        'signature' => $signature,
    ]);
});

Route::post('/meetings/create', 'App\Http\Controllers\MeetingController@create');
Route::get('/meetings/get', 'App\Http\Controllers\MeetingController@get');
Route::post('/meetings/generate-signature', 'App\Http\Controllers\MeetingController@generate_signature');
