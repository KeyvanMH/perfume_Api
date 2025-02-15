<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

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
