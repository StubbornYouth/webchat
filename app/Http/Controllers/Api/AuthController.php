<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // 验证注册字段
        $validator = Validator::make($request->all(), [
            'name' => 'bail|required|email|max:100|unique:users',
            'password' => 'bail|required|string|min:6',
            'src' => 'bail|active_url|max:255'
        ]);
        if ($validator->fails()) {
            $this->error($validator->errors()->first());
        }

        // 在数据库中创建用户并返回
        $email = $request->input('name');
        try {
            $user = User::create([
                'name' => substr($email, 0, strpos($email, '@')),
                'email' => $email,
                'avatar' => $request->input('src'),
                'password' => bcrypt($request->get('password')),
            ]);
            if ($user) {
                $this->response['data'] = $user;
                $this->success('注册成功');
            } else {
                $this->error('注册失败');
            }
        } catch (QueryException $exception) {
            $this->error('保存用户到数据库异常：' . $exception->getMessage());
        }
    }

    public function login(Request $request)
    {
        // 验证登录字段
        $validator = Validator::make($request->all(), [
            'name' => 'required|email|string',
            'password' => 'required|string',
        ]);
        if ($validator->fails()) {
            $this->error($validator->errors()->first());
        }

        $email = $request->input('name');

        $credentials['email'] = $email;
        $credentials['password'] = $request->password;

        if (!$token = \Auth::guard('api')->attempt($credentials)) {
            $this->error('邮箱或密码错误');
        }

        $user = auth('api')->user();
        $user['token'] = $token;
        $this->response['data'] = $user;
        $this->success('登录成功');
        // return $this->respondWithToken($token)->setStatusCode(201);
    }

    public function destroy()
    {
        auth('api')->logout();
        $this->success();
    }

    protected function respondWithToken($token)
    {
        $this->response['data'] = [
            'token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ];
        $this->success();
    }
}
