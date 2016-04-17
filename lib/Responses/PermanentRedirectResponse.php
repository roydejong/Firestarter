<?php

namespace Firestarter\Responses;

use Enlighten\Http\Response;
use Enlighten\Http\ResponseCode;

/**
 * A permanent redirect response that will be cached by the receiving end.
 */
class PermanentRedirectResponse extends RedirectResponse
{
    /**
     * Initializes a HTTP 301 Moved Permanently response with a Location header.
     *
     * @param string $location Redirect location
     */
    public function __construct($location)
    {
        parent::__construct($location);

        $this->setResponseCode(ResponseCode::HTTP_MOVED_PERMANENTLY);
    }
}