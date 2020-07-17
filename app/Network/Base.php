<?php

namespace App\Network;
interface Base
{
    public function get(string $url, array $param);

    public function post(string $url, array $param);

    public function put(string $url, array $param);

    public function patch(string $url, array $param);

    public function delete(string $url, array $param);
}
