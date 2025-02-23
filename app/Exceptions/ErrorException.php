<?php

namespace App\Exceptions;

use Exception;

class ErrorException extends Exception
{
    public function render($request)
    {
        // Return a JSON response for the exception
        return response()->json([
            'message' => $this->message,
        ], 400);
    }
}
