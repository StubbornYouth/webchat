<?php

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

Route::namespace('App\Http\Controllers\Api')->name('api.')->group(function () {
    Route::post('/register', 'AuthController@register');
    Route::post('/login', 'AuthController@login');

    // 登录后可以访问的接口
    Route::middleware('auth:api')->group(function() {
        // 当前登录用户信息
        Route::get('user', 'UserController@me')
            ->name('user.show');

        Route::get('/robot', function (Request $request) {
            $info = $request->input('info');
            $userid = $request->input('id');
            preg_match_all('/[a-zA-Z0-9]/u',$userid, $result);
            $userid = implode('', $result[0]);
            $key = config('services.robot.key');
            $url = config('services.robot.api');
            $client = new \GuzzleHttp\Client();
            $perception = ["inputText"=>["text" => $info]];
            $userInfo = ["apiKey"=>$key,"userId"=>$userid];
            $response = $client->request('POST', $url, [
                'json' => compact("perception","userInfo")
            ]);
            return response()->json(['data' => $response->getBody()->getContents()]);
        });

        Route::get('/history/message', 'MessageController@history');
        Route::post('/file/uploadimg', 'FileController@uploadImage');
        Route::post('/file/avatar', 'FileController@avatar');
    });
});
