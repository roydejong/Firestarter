<?php

namespace Firestarter\Responses;

use Enlighten\Http\Response;
use Enlighten\Http\ResponseCode;

/**
 * A temporary redirect response.
 */
class RedirectResponse extends Response
{
    /**
     * Initializes a HTTP 302 Found response with a Location header.
     *
     * @param string $location Redirect location
     */
    public function __construct($location)
    {
        parent::__construct();

        $this->setResponseCode(ResponseCode::HTTP_FOUND);
        $this->setHeader('Location', $location);
    }

    /**
     * @inheritdoc
     */
    public function send()
    {
        $this->setBody('');

        parent::send();
    }
}