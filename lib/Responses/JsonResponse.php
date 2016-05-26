<?php

namespace Firestarter\Responses;

use Enlighten\Http\Response;
use Enlighten\Http\ResponseCode;

/**
 * A response for serialising data as JSON.
 */
class JsonResponse extends Response
{
    /**
     * Initializes an application/json content response.
     *
     * @param mixed $data The raw data to be encoded (response body content).
     */
    public function __construct($data)
    {
        parent::__construct();

        $this->setResponseCode(ResponseCode::HTTP_OK);
        $this->setHeader('Content-Type', 'application/json');
        $this->setBody(json_encode($data));
    }
}