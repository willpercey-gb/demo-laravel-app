<?php


namespace App\Exceptions\Handlers;


use Illuminate\Support\Str;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use League\OpenAPIValidation\PSR7\Exception\ValidationFailed;
use ReflectionClass;

final class RequestValidationExceptionHandler implements ExceptionHandler
{
    public const TITLE_PREFIX = 'errors.validation.';
    public const ERROR_TYPE = 'validation';
    private ValidationFailed $error;

    public function __construct(ValidationFailed $error)
    {
        $this->error = $error;
    }

    #[ArrayShape(['type' => "string"])]
    public function getSource(): array
    {
        return [
            'type' => self::ERROR_TYPE
        ];
    }

    public function getTitle(): string
    {
        $shortClassName = (new ReflectionClass($this->error))->getShortName();
        return self::TITLE_PREFIX . Str::lower(Str::snake($shortClassName));
    }

    #[Pure]
    public function getDetail(): string
    {
        return $this->error->getMessage();
    }

    #[Pure]
    public function getMeta(): ?array
    {
        return [
            $this->error->getTrace()
        ];
    }
}
