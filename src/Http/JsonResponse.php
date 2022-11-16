<?php

namespace App\kernel\Http;

class JsonResponse extends BaseResponse
{

    public function __construct($body, $status = 200, $headers = [])
    {
        $headers['Content-type'] = 'application/json';

        parent::__construct(json_encode($body), $status, $headers);
    }
}