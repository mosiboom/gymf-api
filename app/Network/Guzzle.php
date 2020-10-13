<?php

namespace App\Network;

use App\Enums\ResponseMessageEnum;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class Guzzle implements Base
{
    private $base_uri;
    private $request;

    public function __construct($base_uri = '')
    {
        $this->base_uri = !$base_uri ? env('PLATFORM_BASE_URL', '') : $base_uri;
        $this->request = new Client([
            // Base URI is used with relative requests
            'base_uri' => $this->base_uri,
            // You can set any number of default request options.
            'timeout' => 10.0,
        ]);
    }

    public function get($url, array $param = [], array $header = [], bool $https = true, int $timeout = 5)
    {
        try {
            $response = $this->request->get($url, [
                'query' => $param
            ]);
            /*$code = $response->getStatusCode(); // 200
            $reason = $response->getReasonPhrase(); // OK
            if ($code != 200) {
                throw new RequestException($reason,'');
            }*/
            $body = $response->getBody();
            return ReturnCorrect(json_decode($body->getContents(), true));
        } catch (RequestException $e) {
            return $this->returnError($e, $url, $param);
        }

    }

    public function post($url, array $param = [], array $header = [], bool $https = true, int $timeout = 5)
    {
        try {
            $response = $this->request->post($url, [
                'form_params' => $param
            ]);
            /*$code = $response->getStatusCode(); // 200
            $reason = $response->getReasonPhrase(); // OK
            if ($code != 200) {
                Log::error("服务器内部请求出现错误:{$code} - {$reason}");
                throw new RequestException('服务器请求有误');
            }*/
            $body = $response->getBody();
            return ReturnCorrect(json_decode($body->getContents(), true));
        } catch (RequestException $e) {
            return $this->returnError($e, $url, $param);
        }
    }

    public function patch($url, array $param = [], array $header = [], bool $https = true, int $timeout = 5)
    {
        try {
            $response = $this->request->patch($url, [
                'headers' => [
                    'Accept' => 'application/json'
                ],
                'json' => $param
            ]);
            /*$code = $response->getStatusCode(); // 200
            $reason = $response->getReasonPhrase(); // OK
            if ($code != 200) {
                Log::error("服务器内部请求出现错误:{$code} - {$reason}");
                throw new RequestException('服务器请求有误');
            }*/
            $body = $response->getBody();
            return ReturnCorrect(json_decode($body->getContents(), true));
        } catch (RequestException $e) {
            return $this->returnError($e, $url, $param);
        }
    }

    public function put($url, array $param = [], array $header = [], bool $https = true, int $timeout = 5)
    {
        try {
            $response = $this->request->put($url, [
                'headers' => [
                    'Accept' => 'application/json'
                ],
                'json' => $param
            ]);
//            $code = $response->getStatusCode(); // 200
//            $reason = $response->getReasonPhrase(); // OK
//            if ($code != 200) {
//                Log::error("服务器内部请求出现错误:{$code} - {$reason}");
//                throw new RequestException('服务器请求有误');
//            }
            $body = $response->getBody();
            return ReturnCorrect(json_decode($body->getContents(), true));
        } catch (RequestException $e) {
            return $this->returnError($e, $url, $param);
        }
    }

    public function delete($url, array $param = [], array $header = [], bool $https = true, int $timeout = 5)
    {
        try {
            $response = $this->request->delete($url, [

            ]);
//            $code = $response->getStatusCode(); // 200
//            $reason = $response->getReasonPhrase(); // OK
//            if ($code != 200) {
//                Log::error("服务器内部请求出现错误:{$code} - {$reason}");
//                throw new RequestException('服务器请求有误');
//            }
            $body = $response->getBody();
            return ReturnCorrect(json_decode($body->getContents(), true));
        } catch (RequestException $e) {
            return $this->returnError($e, $url, $param);
        }
    }

    public function returnError(RequestException $e, $url, $param)
    {
        Log::error("服务器内部请求出现错误:{$e->getCode()} - {$e->getMessage()}", ["param" => $param, 'url' => $url]);
        return ReturnAPI(ResponseMessageEnum::SERVER_ERROR);
    }

    private function returnResult($code, $content)
    {
        return [
            'http_code' => $code,
            'content' => $content,
            'result' => json_decode($content, true)];
    }
}
