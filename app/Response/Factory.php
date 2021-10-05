<?php

namespace App\Response;

use App\Models\Phonebook\Entry;
use App\Response\Schema\EntrySchema;
use Neomerx\JsonApi\Contracts\Encoder\EncoderInterface;
use Neomerx\JsonApi\Encoder\Encoder;

class Factory
{
    private static array $schemas = [
        Entry::class => EntrySchema::class
    ];

    public static function instance(): EncoderInterface
    {
        return Encoder::instance(self::$schemas)
            ->withUrlPrefix(env('APP_URL') . '/v1')
            ->withEncodeOptions(JSON_PRETTY_PRINT);
    }
}
