<?php

namespace App\Services;

use App\Enums\ResponseMessageEnum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadService
{
    # 支持的key
    public $key_array = ['file', 'image', 'media'];
    # 发送过来的key
    public $key = null;
    # 最终上传的文件名(包含后缀)
    public $name;
    # 是否使用原文件名
    public $original = 0;
    # 是否使用path
    public $path;
    # 文件扩展名
    public $extension;
    # 文件对象、request对象
    public $file, $request;
    # 存储错误信息
    public $error = [];

    public function __construct(Request $request, $key, $original = 0)
    {
        $this->request = $request;
        $this->file = $request->file($key);
        $this->original = $original;
        $this->key = $key;
    }

    /**
     * 检测KEY参数是否合法
     * @param $key
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
     * 检测的总方法(客户端调用)
     * @param $key
     * @return $this
     */
    public function check()
    {
        $this->checkKey()->checkFileExist()->checkExtension();
        return $this;
    }

    /**
     * 塞入错误信息
     * @param $code
     * @param $message
     */
    public function setError($code, $message)
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
        $path = $this->file->storeAs("public/{$this->key}/{$time}", $this->name);
        return ReturnCorrect(Storage::url($path));
    }


    # 进入资源库
    public function insertLib()
    {
        $data = [];
    }
}
