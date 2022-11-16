<?php

namespace App\kernel\Http;

class JsonProblemResponse extends BaseResponse
{

    public function __construct($body, $status = 200, $headers = [])
    {
        $headers['Content-type'] = 'application/problem+json';

        parent::__construct(json_encode($body), $status, $headers);
    }
}