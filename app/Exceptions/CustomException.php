<?php

namespace App\Exceptions;

use Exception;
use Log;
class CustomException extends Exception
{
    /**
     * Report the exception.
     *
     * @return void
     */
    public function report(Exception $exception)
    {
        Log::debug($Exception);
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        return response()->view('errors.custom', [], 500);
    }
}