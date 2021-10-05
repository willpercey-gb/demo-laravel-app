<?php

namespace App\Response\Schema;

use App\Models\Phonebook\Entry;
use Neomerx\JsonApi\Contracts\Schema\ContextInterface;
use Neomerx\JsonApi\Schema\BaseSchema;

class EntrySchema extends BaseSchema
{

    public function getType(): string
    {
        return 'entry';
    }

    /** @var Entry $resource */
    public function getId($resource): ?string
    {
        return $resource->uuid;
    }

    /** @var Entry $resource */
    public function getAttributes($resource, ContextInterface $context): iterable
    {
        return $resource->toArray();
    }

    /** @var Entry $resource */
    public function getRelationships($resource, ContextInterface $context): iterable
    {
        return [
//           'phonebook' => [
//
//           ]
        ];
    }
}
