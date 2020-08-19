<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ResponseMessageEnum;
use App\Http\Controllers\Controller;
use App\Http\Middleware\AdminAuthentication;
use App\Models\Admin\AdminUser;
use App\Services\AuthService;
use App\Services\EncryptService;
use Illuminate\Http\Request;
use Monolog\Handler\IFTTTHandler;

class LoginController extends Controller
{
    public $param = ['username', 'password'];

    public function login(Request $request)
    {
        return $this->response(EncryptService::adminLogin($request->only($this->param)));
    }

    public function logout()
    {
        $Auth = new AuthService('admin');
        $jti = $Auth::getJtiFromRequest();
        $Auth->setBlacklist($jti);
        return $this->response(ReturnCorrect());
    }
}
