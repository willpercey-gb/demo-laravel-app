<?php


namespace App\Exceptions\Handlers;

use Illuminate\Support\Str;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use League\OpenAPIValidation\Schema\Exception\SchemaMismatch;
use ReflectionClass;

final class SchemaMismatchExceptionHandler implements ExceptionHandler
{
    public const TITLE_PREFIX = 'errors.validation.';
    public const ERROR_TYPE = 'validation';
    private SchemaMismatch $error;

    public function __construct(SchemaMismatch $error)
    {
        $this->error = $error;
    }

    #[ArrayShape(['type' => "string", 'pointer' => "string"])]
    public function getSource(): array
    {
        return [
            'type' => self::ERROR_TYPE,
            'pointer' => '/' . implode('/', $this->error->dataBreadCrumb()?->buildChain() ?? []),
        ];
    }

    public function getTitle(): string
    {
        $shortClassName = (new ReflectionClass($this->error))->getShortName();
        return self::TITLE_PREFIX . strtolower(Str::snake($shortClassName));
    }

    public function getDetail(): ?string
    {
        if (app()->isProduction()) {
            return null;
        }

        return $this->error->getMessage();
    }

    #[Pure]
    public function getMeta(): ?array
    {
        return [
            $this->error->data()
        ];
    }
}
