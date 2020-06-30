<?php

namespace App\Services;
class JwtTokenService
{
    const VERIFY_SUCCESS = ['code' => 0, 'msg' => '解析成功'];
    const HEADER_ERROR = ['code' => 1, 'msg' => 'header有误!'];
    const SIGN_ERROR = ['code' => 2, 'msg' => '签名有误!'];
    const ISSUE_ERROR = ['code' => 3, 'msg' => '签发有误!'];
    const TOKEN_EXPIRE_ERROR = ['code' => 4, 'msg' => 'token过期!'];
    const TOKEN_NBF_ERROR = ['code' => 5, 'msg' => '不接收处理该Token!'];
    const TOKEN_FORMAT_ERROR = ['code' => 6, 'msg' => '令牌有误!'];
    const TOKEN_TYPE_ERROR = ['code' => 8, 'msg' => 'token类型有误!'];
    const TOKEN_ISS_ERROR = ['code' => 9, 'msg' => 'token身份有误!'];
    //头部
    private static $header = array(
        'alg' => 'HS256', //生成signature的算法
        'typ' => 'JWT'  //类型
    );

    /**
     * 生成jwt token
     * @param array $payload jwt载荷  格式如下非必须
     * [
     * 'iss'=>'jwt_admin', //该JWT的签发者
     * 'iat'=>time(), //签发时间
     * 'exp'=>time()+7200, //过期时间
     * 'nbf'=>time()+60, //该时间之前不接收处理该Token
     * 'sub'=>'www.admin.com', //面向的用户
     * 'jti'=>md5(uniqid('JWT').time()) //该Token唯一标识
     * ]
     * @param $key
     * @return bool|string
     */
    public static function getToken(array $payload, string $key)
    {
        if (is_array($payload)) {
            $base64header = self::base64UrlEncode(json_encode(self::$header, JSON_UNESCAPED_UNICODE));
            $base64payload = self::base64UrlEncode(json_encode($payload, JSON_UNESCAPED_UNICODE));
            return $base64header . '.' . $base64payload . '.' . self::signature($base64header . '.' . $base64payload, $key, self::$header['alg']);
        } else {
            return false;
        }
    }

    /**
     * 验证token是否有效,默认验证exp,nbf,iat时间
     * @param string $token 需要验证的token
     * @param string $key
     * @param string $iss
     * @param string $type token类型
     * @return bool|string|array
     */
    public static function verifyToken(string $token, string $key, string $iss, string $type = "access")
    {
        $tokens = explode('.', $token);
        if (count($tokens) != 3)
            return self::returnCode(self::TOKEN_FORMAT_ERROR);

        list($base64header, $base64payload, $sign) = $tokens;

        # 获取jwt算法
        $base64DecodeHeader = json_decode(self::base64UrlDecode($base64header), JSON_OBJECT_AS_ARRAY);
        if (empty($base64DecodeHeader['alg']))
            return self::returnCode(self::HEADER_ERROR);
        # 签名验证
        if (self::signature($base64header . '.' . $base64payload, $key, $base64DecodeHeader['alg']) !== $sign)
            return self::returnCode(self::SIGN_ERROR);

        # 载荷验证
        $payload = json_decode(self::base64UrlDecode($base64payload), JSON_OBJECT_AS_ARRAY);

        # 签发时间大于当前服务器时间验证失败
        if (isset($payload['iat']) && $payload['iat'] > time())
            return self::returnCode(self::ISSUE_ERROR);

        # 过期时间小于当前服务器时间验证失败
        # 过期时间需要区分类型是因为access_token过期时需要利用refresh_token刷新生成新token
        if ($type == 'access') {
            if (isset($payload['exp']) && $payload['exp'] < time())
                return self::returnCode(self::TOKEN_EXPIRE_ERROR, false, $payload['refresh_id']);

        } else {
            if (isset($payload['exp']) && $payload['exp'] < time())
                return self::returnCode(self::TOKEN_EXPIRE_ERROR);
        }
        # 校验token类型
        if ($payload['type'] != $type)
            return self::returnCode(self::TOKEN_TYPE_ERROR, false);

        # 验证token身份
        if ($payload['iss'] != $iss)
            return self::returnCode(self::TOKEN_ISS_ERROR, false);

        # 该nbf时间之前不接收处理该Token
        /*if (isset($payload['nbf']) && $payload['nbf'] > time()) {
            return self::returnCode(5);
        }*/
        return self::returnCode(self::VERIFY_SUCCESS, $payload);
    }

    /**
     * 返回数据格式化
     * @param array $return_msg
     * @param bool|array $payload
     * @param string $jti
     * @return array
     */
    public static function returnCode(array $return_msg, $payload = false, $jti = '')
    {
        return array(
            'code' => $return_msg['code'],
            'msg' => $return_msg['msg'],
            'payload' => $payload,
            'jti' => $jti
        );
    }


    /**
     * base64UrlEncode  https://jwt.io/ 中base64UrlEncode编码实现
     * @param string $input 需要编码的字符串
     * @return string
     */
    private static function base64UrlEncode(string $input)
    {
        return str_replace('=', '', strtr(base64_encode($input), '+/', '-_'));
    }

    /**
     * base64UrlEncode https://jwt.io/ 中base64UrlEncode解码实现
     * @param string $input 需要解码的字符串
     * @return bool|string
     */
    private static function base64UrlDecode(string $input)
    {
        $remainder = strlen($input) % 4;
        if ($remainder) {
            $addlen = 4 - $remainder;
            $input .= str_repeat('=', $addlen);
        }
        return base64_decode(strtr($input, '-_', '+/'));
    }

    /**
     * HMAC SHA256签名  https://jwt.io/ 中HMAC SHA256签名实现
     * @param string $input 为base64UrlEncode(header).".".base64UrlEncode(payload)
     * @param string $key
     * @param string $alg 算法方式
     * @return mixed
     */
    private static function signature(string $input, string $key, string $alg = 'HS256')
    {
        $alg_config = array(
            'HS256' => 'sha256'
        );
        return self::base64UrlEncode(hash_hmac($alg_config[$alg], $input, $key, true));
    }
}
