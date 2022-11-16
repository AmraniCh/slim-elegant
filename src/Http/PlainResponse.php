<?php

namespace App\kernel\Http;

class PlainResponse extends BaseResponse
{

    public function __construct($body, $status = 200, $headers = [])
    {
        $headers['Content-type'] = 'text/plain';

        parent::__construct($body, $status, $headers);
    }
}