<?php

namespace App\Kernel\Http;

class RedirectResponse extends BaseResponse
{

    public function __construct(string $location, int $status = 301)
    {
        $headers['location'] = $location;

        parent::__construct(null, $status, $headers);
    }
}