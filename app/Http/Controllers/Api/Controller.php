<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\HttpResponseException;

class Controller extends BaseController
{
    protected $response;

    public function __construct() {
        $this->response = [
            'msg' => '请求成功',
            'data' => []
        ];
    }

    protected function success($msg='') {
        if($msg != '') {
            $this->response['msg'] = $msg;
        }
        $this->returnMsg('success');
    }

    protected function error($msg='请求失败，请重试') {
        $this->response['msg'] = $msg;
        $this->returnMsg('error');
    }

    private function returnMsg($type) {
        switch($type) {
            case 'success':
                $code = 1;
                break;
            case 'error':
                $code = 0;
                break;
            default:
                $code = 0;
        }
        throw new HttpResponseException(response()->json(['code'=>$code,'message'=>$this->response['msg'],'data'=>$this->response['data']]));
    }
}
