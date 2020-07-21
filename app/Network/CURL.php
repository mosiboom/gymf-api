<?php

namespace App\Network;
class CURL implements Base
{
    private $ch;
    private $default_header = ["Content-type" => "application/json", "Accept" => "application/json"];
    private $base_uri;
    private $debug;
    private $config;
    private $alone_debug;

    /**
     * @param string $base_uri 基础路径，可以为域名等
     * @param bool $debug 开启debug模式
     * @param array $config
     */
    public function __construct(string $base_uri = '', bool $debug = false, array $config = ['https' => true, 'timeout' => 5, 'header' => []])
    {
        $this->base_uri = $base_uri;
        $this->debug = $debug;
        $this->setConfig($config);
    }

    /**
     * GET方式发起请求
     * @param string $url 请求的URL
     * @param array $param 请求参数
     * @return array
     */
    public function get(string $url, array $param = []): array
    {
        if (!empty($param)) {
            $url .= "?";
            foreach ($param as $k => $v) {
                $url .= "{$k}=$v&";
            }
            $url = substr($url, 0, -1);
        }
        $this->initCURL($url);
        return $this->exec();
    }

    /**
     * POST方式发起请求
     * @param string $url 请求的URL
     * @param array $param 请求参数
     * @return array
     */
    public function post($url, $param = []): array
    {
        $this->initCURL($url);
        $this->setParam($param);
        return $this->exec();
    }

    /**
     * PUT方式发起请求
     * @param string $url 请求的URL
     * @param array $param 请求参数
     * @return array
     */
    public function put($url, $param = []): array
    {
        $this->initCURL($url);
        $this->setParam($param, 'PUT');
        return $this->exec();
    }

    /**
     * DELETE方式发起请求
     * @param string $url 请求的URL
     * @param array $param 请求参数
     * @return array
     */
    public function delete($url, $param = []): array
    {
        $this->initCURL($url);
        $this->setParam($param, 'DELETE');
        return $this->exec();
    }

    /**
     * PATCH方式发起请求
     * @param string $url 请求的URL
     * @param array $param 请求参数
     * @return array
     */
    public function patch($url, $param = []): array
    {
        $this->initCURL($url);
        $this->setParam($param, 'PATCH');
        return $this->exec();
    }

    /**
     * 初始化CURL
     * @param string $url 链接
     */
    private function initCURL(string $url)
    {
        $this->ch = curl_init();
        $url = $this->base_uri . $url;
        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)");
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);//是否直接输出返回流
        curl_setopt($this->ch, CURLOPT_TIMEOUT, $this->config['timeout']);
        # 格式化header
        $header = [];
        foreach ($this->config['header'] as $k => $v) {
            array_push($header, "{$k}:{$v}");
        }
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, $header); //模拟的header头
        if ($this->config['https']) $this->setHttps();
    }

    /**
     * 负责执行CURL的发送操作
     * */
    private function exec()
    {
        # 执行请求
        $result = curl_exec($this->ch);
        # 获取信息
        $httpCode = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
        # 关闭资源
        $this->closeResource();
        return $this->returnResult($httpCode, $result);
    }

    /**
     * 负责将参数添加进去
     * @param array $param 参数
     * @param string $method 参数方法，大写：POST/DELETE/PUT/PATCH
     */
    private function setParam(array $param, $method = 'POST')
    {
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, $method); //定义请求类型，必须为大写
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, json_encode($param));//将数据塞入
    }

    /**
     * 负责关闭CURL资源
     * */
    private function closeResource()
    {
        if ($this->debug) {
            $this->beginDebug();
        }
        curl_close($this->ch);//关闭curl，释放资源
    }

    /**
     * 返回结果格式化
     * @param int $code 返回的HTTP状态码
     * @param // $content 返回的内容
     * @return array
     */
    private function returnResult(int $code, $content): array
    {
        return [
            'http_code' => $code,
            'content' => $content,
            'result' => json_decode($content, true)];
    }

    /**
     * 如果是Https请求需要的设置的配置
     * */
    private function setHttps()
    {
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);//https请求 不验证证书
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, false);//https请求 不验证HOST
    }

    /**
     * 获取请求返回的内部信息
     * */
    private function getReturnInfo()
    {
        return curl_getinfo($this->ch);
    }

    /**
     * 开启debug
     * */
    private function beginDebug()
    {
        //todo 可添加其他debug内容
        $debug = [
            'request_return' => $this->getReturnInfo(),
            'request_config' => $this->config
        ];
        dump($debug);
        if ($this->alone_debug) {
            $this->alone_debug = false;
            $this->debug = false;
        }
        return $this;
    }

    /**
     * 设置默认头部
     * @param $default_header
     * @return CURL
     */
    public function setDefaultHeader(array $default_header = [])
    {
        $this->default_header = $default_header;
        return $this;
    }

    /**
     * 设置头部
     * @param array $header
     * @return CURL
     */
    public function setHeader(array $header = [])
    {
        $this->config['header'] = array_merge($this->default_header, $header);
        return $this;
    }

    /**
     * 设置debug
     * @param bool $debug
     * @return CURL
     */
    public function debug($debug = true)
    {
        $this->debug = $debug;
        $this->alone_debug = true;
        return $this;
    }

    /**
     * 设置配置
     * @param array $config
     * @return CURL
     */
    private function setConfig(array $config = ['https' => true, 'timeout' => 5, 'header' => []])
    {
        $this->config['https'] = $config['https'];
        $this->config['timeout'] = $config['timeout'];
        $this->config['header'] = array_merge($this->default_header, $config['header']);
        return $this;
    }
}
