<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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

Route::get('/', function(){
	return view('welcome');
});


Route::get('prepare-to-login', function () {
    	
    	$state = Str::random(40);

    	//se for numa aplicação front, guardar no localStorage ou algo similar
    	session([
    		'state' => $state
    	]);

    	$query = http_build_query([
    		'client_id' => env('CLIENT_ID'),
    		'redirect_url' => env('REDIRECT_URL') ,
    		'response_type' => 'code',
    		'scope' => '', //Mensagem que vai aparecer na tela de autenticação, se ficar vázio usa a padrão
    		'state' => $state,
    	]);

    	return redirect(env('API_URL').'oauth/authorize?'.$query);

})->name('prepare.login');


//Vai retornar o 'Acess Token' referente a esse usuário, desse client.
Route::get('callback', function(Request $request){
	

	//Verificação de state

	$response = Http::post(env('API_URL').'oauth/token', [
		'grant_type' => 'authorization_code',
		'client_id'  => env('CLIENT_ID'),
		'client_secret' => env('CLIENT_SECRET'),
		'redirect_url' => env('REDIRECT_URL'),
		'code' => $request->code,

	]);

	dd($response->json());
});
