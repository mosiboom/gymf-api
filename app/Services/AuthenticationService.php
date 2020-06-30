<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;

class AuthenticationService
{

    public static function loadConf($conf)
    {
        return config("token.$conf");
    }

    /**
     *
     * @param $uid
     * @param $tokenArr //当前token和refresh_token(如果没有就不用)
     * @param string $iss
     * @return array
     */
    /*    public static function joinBlacklist($uid, $tokenArr, $iss = 'user')
        {
            $expire_time = time() + self::loadConf($iss)['refresh_token_expire_time'];
            Redis::set(self::loadConf('cache_key')['blacklist'] . $uid, json_encode($tokenArr), $expire_time);
        }

        public static function checkBlacklist($uid, $token, $iss = 'user', $type = 'access')
        {
            $str = Redis::get(self::loadConf('cache_key')['blacklist'] . $uid);
            if (!$str) return true;
            if ($type == 'access') {
                if ($token)

            }
        }*/

    /*生成token组*/
    public static function generateToken($uid, $iss = 'user')
    {
        $refresh = self::generateRefreshToken($uid, $iss, true);
        $access = self::generateAccessToken($uid, $iss, true, $refresh['jti']);
        //refresh_token进缓存
        Redis::set(self::loadConf('cache_key')['refresh'] . $refresh['jti'], $refresh['token']);
        return array(
            'access_token' => $access['token'],
            'refresh_token' => $refresh['token'],
            'expires_in' => time() + self::loadConf($iss)['refresh_token_expire_time']
        );
    }

    /**
     * 生成access_token接口
     * @param $uid
     * @param string $iss //签发者身份
     * @param string $refresh_id
     * @param bool $return_jti 是否返回jti
     * @return string | array
     */
    public static function generateAccessToken($uid, $iss = 'user', $return_jti = false, $refresh_id = '')
    {
        $jti = md5(uniqid($iss) . time());
        $exp = self::loadConf($iss)['access_token_expire_time'];
        $data = array(
            'iss' => self::loadConf($iss)['secret_user'], //该JWT的签发者
            'iat' => time(), //签发时间
            'exp' => time() + $exp, //过期时间
            'nbf' => time() + 10, //该时间之前不接收处理该Token
            'uid' => $uid,
            'type' => 'access',
            'refresh_id' => $refresh_id,
            'jti' => $jti   //该token的id
        );
        if ($return_jti) {
            return array(
                'jti' => $jti,
                'token' => SerJwtToken::getToken($data, self::loadConf($iss)['secret_key'])
            );
        }
        return SerJwtToken::getToken($data, self::loadConf($iss)['secret_key']);
    }

    /**
     * 生成refresh_token接口
     * @param $uid
     * @param string $iss
     * @param bool $return_jti 是否返回jti
     * @return array | string
     */
    public static function generateRefreshToken($uid, $iss = 'user', $return_jti = false)
    {
        $jti = md5(uniqid($iss) . time());
        $exp = self::loadConf($iss)['refresh_token_expire_time'];
        $data = array(
            'iss' => self::loadConf($iss)['secret_user'], //该JWT的签发者
            'iat' => time(), //签发时间
            'exp' => time() + $exp, //过期时间
            'nbf' => time() + 60, //该时间之前不接收处理该Token
            'uid' => $uid,
            'type' => 'refresh',
            'jti' => $jti
        );
        if ($return_jti) {
            return array(
                'jti' => $jti,
                'token' => SerJwtToken::getToken($data, self::loadConf($iss)['secret_key'])
            );
        }
        return SerJwtToken::getToken($data, self::loadConf($iss)['secret_key']);
    }

    /**
     * 验证token
     * @param string $token 要验证的token
     * @param string $iss 用户身份
     * @param string $type
     * @param bool $is_black 是否使用黑名单
     * @param string $black_key 若使用黑名单则输入key
     * @return array|bool|string
     */
    public static function verifyToken(string $token, $iss = 'user', $type = 'access', $is_black = false, $black_key = '')
    {
        $res = SerJwtToken::verifyToken($token, self::loadConf($iss)['secret_key'], self::loadConf($iss)['secret_user'], $type);
        if ($res['code'] == 0) {
            return array('token' => $token, 'code' => $res['code'], 'payload' => $res['payload']);
        }

        return array('token' => false, 'code' => self::loadConf('code')[$res['code']]);//输出对应代码
    }

    /**
     * 验证token 失败返回false,成功返回原/新access_token
     * @param string $token access_token
     * @param string $auth_id //refresh_token md5 加密生成的
     * @param string $iss
     * @return array
     */
    public static function generateNewAccess($token, $auth_id, $iss = 'user')
    {
        /*验证有效性*/
        $res = SerJwtToken::verifyToken($token, self::loadConf($iss)['secret_key'], self::loadConf($iss)['secret_user']);
        if ($res['code'] == 0) {
            return [
                'code' => 0,
                'token' => $token,
            ];
        }
        /*不是过期，说明token有误*/
        if ($res['code'] != 4) {
            return [
                'code' => self::loadConf('code')[$res['code']],
                'token' => false
            ];
        }
        /*access过期生成新token*/
        $refresh_id = $res['jti'];
        $refresh = Redis::get(self::loadConf('cache_key')['refresh'] . $refresh_id);//获取在缓存里的refresh_token
        if ($auth_id == md5($refresh)) { //校验$auth_id是否合格
            $auth_res = self::verifyToken($refresh, $iss, 'refresh');
            if (!$auth_res['token']) return ['code' => $auth_res['code'], 'token' => ''];
            return [
                'token' => self::generateAccessToken($auth_res['payload']['uid'], $iss, false, $auth_res['payload']['jti']),
                'code' => 0
            ];
        } else {
            return [
                'code' => self::loadConf('code')[7],
                'token' => ''
            ];
        }
    }

    /*获取Bearer token*/
    public static function getFinalToken(string $token)
    {
        return trim(str_replace("Bearer", "", $token));
    }

    #代理token（扩展）
    public static function OauthToken($uid)
    {
        $token = self::makeToken($uid);
        //todo 校验client_id
        return [
            'token' => $token['access_token'],
            'auth_id' => md5($token['refresh_token']),
            'expires_in' => time() + self::loadConf('refresh_token_expire_time')
        ];
    }

    # 获取token解析后的id
    public static function getUserIdByToken($iss = 'user')
    {
        $Auth = request()->header('Authorization');

        if (!$Auth) {
            return null;
        }

        $token = SerAuth::getFinalToken($Auth);
        if (!$token) {
            return null;
        }
        $result = SerAuth::verifyToken($token, $iss);

        if (!$result['token']) {
            return null;
        }
        return $result['payload']['uid'];
    }

}
