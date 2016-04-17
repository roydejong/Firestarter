<?php

namespace Firestarter\Forms;

/**
 * Form "method" attribute enum.
 * Controls how a form is submitted and validated.
 */
class FormMethod
{
    /**
     * POST method: the form is serialized and submitted via HTTP POST.
     */
    const POST = "post";

    /**
     * GET method: the form is serialized and submitted as a query string via HTTP GET.
     */
    const GET = "get";
}