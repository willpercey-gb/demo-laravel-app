<?php

namespace App\Response;

use Illuminate\Http\JsonResponse;

class JsonSuccessResponse extends JsonResponse
{
    public function __construct(
        array|\stdClass|null $data = null,
        array|\stdClass|null $meta = null,
        int $status = 200,
        array $headers = [],
        int $options = 0,
        bool $json = false
    ) {
        parent::__construct(
            array_filter(compact('data', 'meta'), static fn($item) => $item !== null),
            $status,
            $headers,
            $options,
            $json
        );
    }
}
