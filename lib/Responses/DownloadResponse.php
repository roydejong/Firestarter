<?php

namespace Firestarter\Responses;

use Enlighten\Http\Response;

/**
 * A response for transmitting data as a downloadable file.
 */
class DownloadResponse extends Response
{
    /**
     * FileResponse constructor.
     * 
     * @param string $data The raw data to be served.
     * @param string $fileName The file name that should be presented.
     * @param string $contentType The content-type that identifies the file.
     */
    public function __construct($data = '', $fileName = 'download', $contentType = 'application/octet-stream')
    {
        parent::__construct();

        $this->setBody($data);
        $this->setHeader('Content-Type', $contentType);
        $this->setHeader('Content-Disposition', sprintf('attachment; filename="%s"'));
    }
}