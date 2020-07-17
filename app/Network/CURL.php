<?php

namespace App\Network;
class CURL implements Base
{
    private $ch;
    private $default_header = ["Content-type:application/json", "Accept:application/json"];
    private $header;
    private $base_uri;
    private $debug;
    private $https;
    private $timeout;

    /**
     * @param string $base_uri 基础路径，可以为域名等
     * @param bool $debug 开启debug模式
     * @param array $config
     */
    public function __construct($base_uri = '', $debug = false, $config = ['https' => true, 'timeout' => 5, 'header' => []])
    {
        $this->base_uri = $base_uri;
        $this->debug = $debug;
        $this->setConfig($config);
    }

    private function initCURL($url)
    {
        $this->ch = curl_init();
        $url = $this->base_uri . $url;
        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)");
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);//是否直接输出返回流
        curl_setopt($this->ch, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, $this->header); //模拟的header头
        if ($this->https) $this->setHttps();
    }

    public function setConfig($config = ['https' => true, 'timeout' => 5, 'header' => []])
    {
        $this->https = $config['https'];
        $this->timeout = $config['timeout'];
        $this->header = array_merge($this->default_header, $config['header']);
    }

    public function get($url, $param = [], $config = [])
    {
        if (!empty($config)) $this->setConfig($config);
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

    public function post($url, $param = [], $config = [])
    {
        if (!empty($config)) $this->setConfig($config);
        $this->initCURL($url);
        $this->setParam($param);
        return $this->exec();

    }

    public function put($url, $param = [], $config = [])
    {
        if (!empty($config)) $this->setConfig($config);
        $this->initCURL($url);
        $this->setParam($param, 'PUT');
        return $this->exec();
    }

    public function delete($url, $param = [], $config = [])
    {
        if (!empty($config)) $this->setConfig($config);
        $this->initCURL($url);
        $this->setParam($param, 'DELETE');
        return $this->exec();
    }

    public function patch($url, $param = [], $config = [])
    {
        if (!empty($config)) $this->setConfig($config);
        $this->initCURL($url);
        $this->setParam($param, 'PATCH');
        return $this->exec();
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
     * @param void $content 返回的内容
     * @return array
     */
    private function returnResult(int $code, $content)
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
        dump($this->getReturnInfo());
        //todo 可添加其他debug内容
    }
}
