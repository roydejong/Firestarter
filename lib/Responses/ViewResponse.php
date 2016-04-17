<?php

namespace Firestarter\Responses;

use Enlighten\Http\Response;
use Firestarter\Views\View;

/**
 * A response that wraps a view to output.
 */
class ViewResponse extends Response
{
    /**
     * The view being output.
     *
     * @var View
     */
    private $view;

    /**
     * Creates a new View-based response.
     */
    public function __construct(View $view)
    {
        parent::__construct();

        $this->view = $view;
    }

    /**
     * @inheritdoc
     */
    public function send()
    {
        $this->setBody($this->view->render() . $this->getBody());

        parent::send();
    }
}