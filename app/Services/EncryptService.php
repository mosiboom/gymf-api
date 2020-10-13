<?php

c
use App\Enums\ResponseMessageEnum;
use App\Models\Admin\AdminUser;

class EncryptService
{
    public static $param = ['username', 'password'];

    # Str::random(32)生成
    private static $admin_secret = 'mhVWKRwdcbXcY4qJ';

    # 校验管理员用户密码
    public static function authAdminUser($password, $db)
    {
        $password = self::makeAdminUser($password);
        if ($password != $db) return false;
        return true;
    }

    # 生成管理员用户密码
    public static function makeAdminUser($password)
    {
        return md5(md5($password . self::$admin_secret));
    }

    # 管理员登录
    public static function adminLogin($form)
    {
        if (!checkEmptyArray($form, self::$param)) {
            return ReturnAPI(ResponseMessageEnum::API_PARAM_ERROR,'用户名或密码为空');
        }
        $admin = AdminUser::query()->where(['username' => $form['username']])->first();
        if (!$admin) {
            return ReturnAPI(ResponseMessageEnum::NAME_OR_PWD_ERROR);
        }
        if (!self::authAdminUser($form['password'], $admin->password)) {
            return ReturnAPI(ResponseMessageEnum::NAME_OR_PWD_ERROR);
        }
        $auth = new AuthService('admin', $admin->id);
        $token = $auth->generateAccess();
        return ReturnCorrect(['token'=>$token['token']]);
    }
}
