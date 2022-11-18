<?php

namespace App\Kernel\Http;

class HtmlResponse extends BaseResponse
{

    public function __construct($body, $status = 200, $headers = [])
    {
        $headers['Content-type'] = 'text/html';

        parent::__construct($body, $status, $headers);
    }
}