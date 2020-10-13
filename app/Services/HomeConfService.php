<?php

namespace App\Services;
/*首页配置*/

use App\Enums\ResponseMessageEnum;

class HomeConfService
{
    private $position;
    private static $instance;

    private function __construct()
    {
        $this->position = storage_path('app/home.json');
    }

    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new HomeConfService();
        }
        return self::$instance;
    }

    public static function set($data)
    {
        $instance = self::getInstance();
        return $instance->setFile($data);
    }

    public static function get()
    {
        $instance = self::getInstance();
        return $instance->getFile();
    }


    public function setFile($data)
    {
        $file = fopen($this->position, "w");
        if (!$file) return ReturnAPI(ResponseMessageEnum::FILE_READ_ERROR);
        if (!fwrite($file, $data)) return ReturnAPI(ResponseMessageEnum::FILE_SET_ERROR);
        fclose($file);
        return ReturnCorrect();
    }

    public function getFile()
    {
        return ReturnCorrect(file_get_contents($this->position));
    }
}
