<?php

namespace App\Exceptions;

use Exception;

class InvalidMimeTypeException extends Exception
{
    public function render($request)
    {
        // Return a JSON response for the exception
        return response()->json([
            'message' => $this->message,
        ], 400);
    }
}
