<?php

namespace App\Events\Commands;

use App\DTO\Casters\CarbonImmutableCaster;
use App\Exceptions\ValidationException;
use Carbon\CarbonImmutable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Spatie\DataTransferObject\Attributes\DefaultCast;
use Spatie\DataTransferObject\DataTransferObject;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validation;

abstract class AbstractMessage extends DataTransferObject implements ShouldQueue
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    #[Assert\Uuid]
    #[Assert\NotBlank]
    protected string $messageUuid;

    public function __construct(...$args)
    {
        $this->messageUuid = Uuid::v4();

        if (is_array($args[0] ?? null)) {
            parent::__construct(...$args);
        } else {
            parent::__construct($args);
        }
    }

    public function getUuid(): string
    {
        return $this->messageUuid;
    }

    public function validate(?object $dataTransferObject = null): void
    {
        $target = $dataTransferObject ?? $this;

        $validator = Validation::createValidator();
        $class = new \ReflectionClass($target);

        $properties = array_filter(
            $class->getProperties(),
            static fn(\ReflectionProperty $property) => !$property->isStatic()
        );

        foreach ($properties as $property) {
            $property->setAccessible(true);

            /** @note Conceptually validate collections of objects */
//            if (is_object($value = $property->getValue($target))) {
//                if ($value instanceof ValidateCollection) {
//                    foreach ($value->getIterator() as $value) {
//                        $this->validate($value);
//                    }
//                    continue;
//                }
//                $this->validate($value);
//            }

            $attributes = $property->getAttributes(Constraint::class, \ReflectionAttribute::IS_INSTANCEOF);
            $constraint = array_map(
                static fn(\ReflectionAttribute $attribute) => $attribute->newInstance(),
                $attributes
            );

            $errors = $validator->validate($property->getValue($target), $constraint);
            if ($errors->count()) {
                throw (new ValidationException($property->getName()))
                    ->setErrors($errors)
                    ->setContext($property->getName());
            }
        }
    }
}
