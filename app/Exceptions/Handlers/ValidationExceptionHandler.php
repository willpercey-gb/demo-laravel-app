<?php


namespace App\Exceptions\Handlers;

use App\Exceptions\ValidationException;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\Iban;
use Symfony\Component\Validator\Constraints\Ip;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\LessThan;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotEqualTo;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Unique;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Validator\Constraints\Uuid;
use Symfony\Component\Validator\ConstraintViolation;

final class ValidationExceptionHandler implements ExceptionHandler
{
    private ConstraintViolation $error;

    public const TITLE_PREFIX = 'errors.validation.';
    public const SOURCE_PREFIX = 'data.attributes.';
    public const ERROR_TYPE = 'validation';

    public static array $map = [
        Date::class => [
            'title' => 'date.',
            'options' => [
                'fallback' => 'unexpected',
                Date::INVALID_DATE_ERROR => 'invalid',
                Date::INVALID_FORMAT_ERROR => 'bad_format'
            ]
        ],
        DateTime::class => [
            'title' => 'date.',
            'options' => [
                'fallback' => 'unexpected',
                DateTime::INVALID_DATE_ERROR => 'invalid',
                DateTime::INVALID_FORMAT_ERROR => 'bad_format',
                DateTime::INVALID_TIME_ERROR => 'invalid_time'
            ]
        ],
        Email::class => [
            'title' => 'email.',
            'options' => [
                'fallback' => 'unexpected',
                Email::INVALID_FORMAT_ERROR => 'bad_format'
            ]
        ],
        Iban::class => [
            'title' => 'iban.',
            'options' => [
                'fallback' => 'unexpected',
                Iban::INVALID_FORMAT_ERROR => 'bad_format',
                Iban::CHECKSUM_FAILED_ERROR => 'checksum_failed'
            ]
        ],
        Ip::class => [
            'title' => 'ip_address.',
            'options' => [
                'fallback' => 'unexpected',
                Ip::INVALID_IP_ERROR => 'invalid',
            ]
        ],
        NotEqualTo::class => [
            'title' => 'not_equal_to.',
            'parameter' => 'compared_value'
        ],
        Required::class => ['title' => 'required.'],
        Type::class => [
            'title' => 'type.',
            'options' => [
                'fallback' => 'unexpected',
                Type::INVALID_TYPE_ERROR => 'invalid'
            ],
            'parameter' => 'type'
        ],
        Unique::class => ['title' => 'unique.'],
        Url::class => [
            'title' => 'url.',
            'options' => [
                'fallback' => 'unexpected',
                Url::INVALID_URL_ERROR => 'invalid'
            ]
        ],
        Uuid::class => [
            'title' => 'uuid.',
            'options' => [
                'fallback' => 'unexpected',
                Uuid::TOO_LONG_ERROR => 'long',
                Uuid::TOO_SHORT_ERROR => 'short',
                Uuid::INVALID_CHARACTERS_ERROR => 'invalid_characters',
                Uuid::INVALID_HYPHEN_PLACEMENT_ERROR => 'invalid_hyphen',
                Uuid::INVALID_VARIANT_ERROR => 'invalid_variant',
                Uuid::INVALID_VERSION_ERROR => 'invalid_version'
            ]
        ],
        Length::class => [
            'title' => 'length.',
            'options' => [
                'fallback' => 'unexpected',
                Length::INVALID_CHARACTERS_ERROR => 'invalid_characters',
                Length::TOO_SHORT_ERROR => 'short',
                Length::TOO_LONG_ERROR => 'long',
            ],
            'parameter' => 'limit',
        ],
        GreaterThan::class => [
            'title' => 'greater_than.',
            'parameter' => 'compared_value'
        ],
        GreaterThanOrEqual::class => [
            'title' => 'greater_than_or_equal_to.',
            'parameter' => 'compared_value'
        ],
        LessThan::class => [
            'title' => 'less_than.',
            'parameter' => 'compared_value'
        ],
        LessThanOrEqual::class => [
            'title' => 'less_than_or_equal_to.',
            'parameter' => 'compared_value'
        ],
        NotBlank::class => ['title' => 'not_blank.'],
        NotNull::class => ['title' => 'not_null.'],
        'fallback' => ['title' => 'unexpected.'],
    ];
    private ?Constraint $constraint;

    public function __construct(ConstraintViolation $error, private ValidationException $exception)
    {
        $this->error = $error;
        $this->constraint = $error->getConstraint();
    }

    #[ArrayShape(['pointer' => "string", 'type' => "string"])]
    public function getSource(): array
    {
        $source = [
            'pointer' => self::SOURCE_PREFIX . Str::snake(
                    !empty($this->error->getPropertyPath()) ? $this->error->getPropertyPath(
                    ) : $this->exception->getContext()
                ),
            'type' => self::ERROR_TYPE
        ];
        if (!isset($this->constraint->fields, $this->constraint->groups)) {
            return $source;
        }

        $property = $this->error->getPropertyPath();
        if (is_array($this->constraint->fields)) {
            $parameter = collect($this->constraint->fields)->filter(fn($item) => $item !== $property)->first();
        } else {
            $parameter = $this->constraint->fields;
        }
        $parameter = $parameter ?: $property;

        $source['pointer'] = self::SOURCE_PREFIX . Str::snake($parameter);

        return $source;
    }

    #[Pure]
    public function getTitle(): string
    {
        $property = $this->constraint ? get_class($this->constraint) : 'fallback';
        $map = self::$map[$property] ?? self::$map['fallback'];

        $translation = self::TITLE_PREFIX . $map['title'];
        if (isset($map['options'][$this->error->getCode()])) {
            $translation .= $map['options'][$this->error->getCode()];
        } elseif (isset($map['options'])) {
            $translation .= 'unexpected';
        }

        return trim($translation, '.');
    }

    public function getDetail(): ?string
    {
        if (app()->isProduction()) {
            return null;
        }

        $detail = $this->constraint ? get_class($this->constraint) : '';

        return $detail . ' | ' . $this->error->getMessage();
    }

    #[Pure]
    public function getMeta(): ?array
    {
        $property = $this->constraint ? get_class($this->constraint) : 'fallback';
        $map = self::$map[$property] ?? self::$map['fallback'];
        $meta = [];

        if (isset($map['parameter'], $this->error->getParameters()["{{ {$map['parameter']} }}"])) {
            $meta['context'] = $this->error->getParameters()["{{ {$map['parameter']} }}"];
        }

        return $meta;
    }
}
