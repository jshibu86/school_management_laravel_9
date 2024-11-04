<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class GeneralJsonException extends Exception
{
    /**
     * Report the Exception
     *
     * @return void
     */
    public function report()
    {
    }
    /**
     * render the exception in HTTP response
     *
     * @params  $request
     */
    public function render($request)
    {
        return new JsonResponse(
            [
                "error" => [
                    "message" => $this->getMessage(),
                ],
            ],
            $this->code
        );
    }
}
