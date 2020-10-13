<?php

namespace App\Services;

use App\Enums\ResponseMessageEnum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * @property $key_array array 支持的key
 * @property $key string 发送过来的key
 * @property $original int 是否使用原文件名
 * @property $path string 返回的路径path
 * @property $extension string 文件扩展名
 * @property $name string 最终上传的文件名(包含后缀)
 * @property $file object 文件对象
 * @property $request object request对象
 * @property $error array 存储错误信息
 * @property $limit_size int 文件大小限制(单位：m)
 * */
class UploadService
{
    private $key_array = ['file', 'image', 'media'], $key, $original, $path, $extension, $file, $request, $name, $limit_size = 2;
    private $error = [];

    public function __construct(Request $request, $key, $original = 0)
    {
        $this->request = $request;
        $this->file = $request->file($key);
        $this->original = $original;
        $this->key = $key;
    }

    /**
     * 检测KEY参数是否合法
     * @return $this
     */
    public function checkKey()
    {
        if (!in_array($this->key, $this->key_array)) {
            $this->setError(1, '不存在的参数');
        }
        return $this;
    }

    /**
     * 检测文件是否存在
     * @return $this
     */
    public function checkFileExist()
    {
        if (!$this->request->hasFile($this->key)) {
            $this->setError(2, '文件不存在');
        }
        return $this;
    }

    /**
     * 检测文件类型
     * @param array $user_ext 客户端控制类型
     * @return UploadService
     */
    public function checkExtension($user_ext = [])
    {
        if (!$this->file) {
            return $this;
        }
        $this->extension = $this->file->getClientOriginalExtension();
        if (!empty($user_ext)) {
            # 用户优先级最高
            $extensions = $user_ext;
        } else {
            switch ($this->key) {
                case 'image':
                {
                    $extensions = ['jpeg', 'png', 'gif', 'jpg'];
                    break;
                }
                case 'media':
                {
                    $extensions = [
                        '3g2', '3ga', '3gpp', '3gp', 'aac', 'avi',
                        'flv', 'mp3', 'mp4', 'webm', 'ogg', 'ogv', 'wmv'
                    ];
                    break;
                }
                default:
                {
                    $extensions = '*';
                }
            }
        }
        if ($extensions == '*') return $this;
        if (!in_array($this->extension, $extensions)) {
            $this->setError(3, '文件类型不匹配');
        }
        return $this;
    }

    /**
     * 检测文件大小
     * @param int $user_limit
     * @return UploadService
     */
    public function checkFileSize($user_limit = 0)
    {
        if (!$this->file) {
            return $this;
        }
        if ($user_limit !== 0) $this->limit_size = $user_limit;
        if (formatBytes($this->file->getSize()) > $this->limit_size) {
            $this->setError('4', "上传的文件不得超过{$this->limit_size}M！");
        }
        return $this;
    }

    /**
     * 检测的总方法(客户端调用)
     * @return $this
     */
    public function check()
    {
        $this->checkKey()->checkFileExist()->checkExtension()->checkFileSize();
        return $this;
    }

    /**
     * 塞入错误信息
     * @param $code
     * @param $message
     */
    private function setError($code, $message)
    {
        array_push($this->error, [
            'code' => $code,
            'message' => $message
        ]);
    }

    /**
     * 上传动作
     * @return array
     */
    public function upload()
    {
        if (!empty($this->error)) {
            return ReturnAPI(ResponseMessageEnum::API_PARAM_ERROR, $this->error[0]['message']);
        }
        $time = date('Ymd');
        if ($this->original == 1) {
            $this->name = $this->file->getClientOriginalName();
        } else {
            $this->name = md5(time()) . Str::random(8) . "." . $this->extension;
        }
        $this->path = $this->file->storeAs("public/{$this->key}/{$time}", $this->name);

        return ReturnCorrect([
            'path' => Storage::url($this->path),
            'name' => $this->getName(),
            'extension' => $this->getExtension()
        ]);
    }

    /**
     * 获取上传后的地址
     * */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * 获取上传后的名字
     * */
    public function getName()
    {
        return $this->name;
    }

    /**
     * 获取上传文件的后缀
     * */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * 获取所有错误信息
     * */
    public function getError()
    {
        return $this->error;
    }
}
