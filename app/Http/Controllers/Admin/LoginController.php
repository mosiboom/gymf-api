<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ResponseMessage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $param = ['username', 'password'];
        $form = $request->only($param);
        if (!checkEmptyArray($form, $param)) {
            return ReturnAPI(ResponseMessage::LOGIN_ERROR);
        }
        //todo 数据库验证用户名密码
        return ReturnCorrect();
    }

    public function logout()
    {

    }
}
