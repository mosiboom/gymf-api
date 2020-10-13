<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class AuthService
{
    public $iss, $uid, $conf, $cache_key, $error_code;
    const ACCESS = 'access';
    const REFRESH = 'refresh';

    public function __construct($iss, $uid = '')
    {
        $this->iss = $iss;
        $this->uid = $uid;
        $configure = config("jwt");
        $this->conf = $configure["$iss"];
        $this->cache_key = $configure["cache_key"];
        $this->error_code = $configure["code"];
    }

    /**
     * 生成token组
     * */
    public function generateGroup()
    {
        # 先生成refresh_token获取其jti
        $refresh = $this->generateRefresh(true);
        # 讲refresh_token的jti传入access_token将其绑定
        $access = $this->generateAccess(true, $refresh['jti']);
        # refresh_token进缓存
        $this->setRefreshCache($refresh['jti'], $refresh['token']);
        return [
            'access_token' => $access['token'],
            'auth_id' => $this->getAuthId($refresh['token']),
            'expires_in' => time() + $this->conf['refresh_token_expire_time'] //显示用，并无特殊作用
        ];
    }

    /**
     * refresh_token缓存
     * @param $jti
     * @param $token
     */
    public function setRefreshCache($jti, $token)
    {
        Cache::put($this->cache_key['refresh'] . $jti, $token);
    }

    /**
     * 获取refresh_token缓存
     * @param $jti
     * @return mixed
     */
    public function getRefreshCache($jti)
    {
        return Cache::get($this->cache_key['refresh'] . $jti);
    }

    /**
     * 生成access_token
     * @param bool $return_jti 是否返回jti
     * @param string $refresh_id 绑定的refresh_token的ID，如果是token组则需要
     * @return array
     */
    public function generateAccess($return_jti = false, $refresh_id = '')
    {
        # 生成标识符
        $data = $this->returnPayload(self::ACCESS, $refresh_id);
        return [
            'jti' => $return_jti ? $data['jti'] : '',
            'token' => JwtTokenService::getToken($data, $this->conf['secret_key'])
        ];
    }

    /**
     * 生成refresh_token
     * @param bool $return_jti 是否返回jti
     * @return array
     */
    public function generateRefresh($return_jti = false)
    {
        $data = $this->returnPayload(self::REFRESH);
        return array(
            'jti' => $return_jti ? $data['jti'] : '',
            'token' => JwtTokenService::getToken($data, $this->conf['secret_key'])
        );
    }

    /**
     * 返回token的载荷
     * @param string $type token类型 access|refresh
     * @param string $refresh_id
     * @return array
     */
    public function returnPayload($type = 'access', $refresh_id = '')
    {
        $jti = md5(uniqid($this->iss) . time());
        $data = [];
        if ($type == self::ACCESS) {
            $exp = $this->conf['access_token_expire_time'];
            $data['refresh_id'] = $refresh_id;
        } else {
            $exp = $this->conf['refresh_token_expire_time'];
        }

        $data = array_merge(
            array(
                'iss' => $this->conf['secret_user'], //该JWT的签发者
                'iat' => time(), //签发时间
                'exp' => time() + $exp, //过期时间
                //'nbf' => time() + 10, //该时间之前不接收处理该Token
                'uid' => $this->uid,
                'type' => $type,
                'jti' => $jti   //该token的id
            ), $data);
        return $data;
    }

    /**
     * 校验access token
     * @param string $access_token
     * @return array
     */
    public function verifyAccess(string $access_token)
    {
        return $this->verify($access_token, self::ACCESS);
    }

    /**
     * 校验refresh token
     * @param string $refresh_token
     * @return array
     */
    public function verifyRefresh(string $refresh_token)
    {
        return $this->verify($refresh_token, self::REFRESH);
    }

    public function verify(string $token, $type)
    {
        # 校验token合法性
        $res = JwtTokenService::verifyToken($token, $this->conf['secret_key'], $this->conf['secret_user'], $type);
        if ($res['code'] == 0) {
            # 查看黑名单中是否存在
            $check_blacklist = $this->getBlacklist($res['payload']['jti']);
            if ($check_blacklist) {
                return ['token' => false, 'code' => $this->error_code[5]];
            }
            return ['token' => $token, 'code' => $this->error_code[$res['code']], 'payload' => $res['payload']];
        }
        # 输出对应错误代码
        return ['token' => false, 'code' => $this->error_code[$res['code']]];
    }


    /**
     * 验证token 失败返回false,成功返回原/新access_token
     * @param string $token access_token
     * @param string $auth_id refresh_token md5 加密生成的
     * @return array
     */
    public function generateNewAccess($token, $auth_id)
    {
        # 验证有效性
        $res = $this->verifyAccess($token);
        if ($res['code'] == 0) {
            return [
                'code' => $this->error_code[0],
                'token' => $token,
            ];
        }
        # 不是过期，说明token有误
        if ($res['code'] != 4) {
            return [
                'code' => $this->error_code[$res['code']],
                'token' => false
            ];
        }
        # access过期生成新token
        $refresh_id = $res['jti'];
        # 获取在缓存里的refresh_token
        $refresh = $this->getRefreshCache($refresh_id);
        if ($auth_id == $this->getAuthId($refresh)) { //校验$auth_id是否合格
            $auth_res = $this->verifyRefresh($refresh);
            if (!$auth_res['token']) return ['code' => $this->error_code[$auth_res['code']], 'token' => ''];
            return [
                'token' => $this->generateAccess(false, $auth_res['payload']['jti'])['token'],
                'code' => $this->error_code[0]
            ];
        }
        return [
            'code' => $this->error_code[7],
            'token' => ''
        ];

    }

    /**
     * 根据refresh_token 生成auth_id
     * @param $refresh_token
     * @return string
     */
    public function getAuthId($refresh_token)
    {
        return md5($refresh_token);
    }

    /*获取http请求头 Bearer token*/
    public function getFinalToken(string $token)
    {
        if (strpos($token, 'Bearer ') === false) {
            return false;
        }
        return trim(str_replace("Bearer", "", $token));
    }

    /*代理token（扩展）todo*/
    public function OauthToken()
    {
        $token = $this->generateGroup();
        //todo 校验client_id
        return [
            'token' => $token['access_token'],
            'auth_id' => md5($token['refresh_token']),
//            'expires_in' => time() + self::loadConf('refresh_token_expire_time')
        ];
    }

    /*获取token解析后的id*/
    public static function getUserIdFromRequest()
    {
        return request()->offsetGet('payload_uid');
    }

    /*获取token解析后的id*/
    public static function getJtiFromRequest()
    {
        return request()->offsetGet('payload_jti');
    }

    /*
     * 塞入token解析后的id
     * */
    public static function setUserFromRequest($payload)
    {
        request()->offsetSet('payload_uid', $payload['uid']);
        request()->offsetSet('payload_jti', $payload['jti']);
    }

    /**
     * 设置黑名单
     * @param $jti
     * @param string $type
     */
    public function setBlacklist($jti, $type = 'access')
    {
        $expire_time = 0;
        if ($type == 'access') {
            $expire_time = $this->conf['access_token_expire_time'];
        } else {
            $expire_time = $this->conf['refresh_token_expire_time¬'];
        }
        Cache::put($this->cache_key['blacklist'] . $jti, $jti, $expire_time);
    }

    /**
     * 获取黑名单
     * @param $jti
     * @return mixed
     */
    public function getBlacklist($jti)
    {
        return Cache::get($this->cache_key['blacklist'] . $jti);
    }

}
