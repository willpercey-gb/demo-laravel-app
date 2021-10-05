<?php

namespace App\Response;

use App\Exceptions\Handlers\DisplayHandler;
use App\Exceptions\Handlers\ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class JsonErrorResponse extends JsonResponse
{
    public function __construct(DisplayHandler $exception, ?int $status = null)
    {
        parent::__construct(
            [
                'errors' => $exception->getErrors()->map(fn(ExceptionHandler $handler) => array_filter(
                    [
                        'source' => $handler->getSource(),
                        'language_key' => $handler->getTitle(),
                        'detail' => $handler->getDetail(),
                        'meta' => $handler->getMeta()
                    ]
                ))->values()->toArray(),
            ],
            $status ?? Response::HTTP_BAD_REQUEST,
        );
    }
}
