<?php

namespace App\Http\Controllers;

use App\Services\UploadService;
use Illuminate\Http\Request;

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
        return $this->response((new UploadService($this->request, $key, $original))->check()->upload());
    }
}
