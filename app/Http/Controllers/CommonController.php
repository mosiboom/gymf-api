<?php

namespace App\Http\Controllers;

use App\Services\UploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CommonController extends Controller
{
    public $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    # 文件上传
    public function upload($key, int $original = 0)
    {
        $uploadService = new UploadService($this->request, $key, $original);
        return $this->response($uploadService->check()->upload());
    }
}
