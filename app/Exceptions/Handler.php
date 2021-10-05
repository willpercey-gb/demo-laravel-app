<?php

namespace App\Exceptions;

use App\Exceptions\Handlers\DisplayHandler;
use App\Exceptions\Handlers\RequestValidationExceptionHandler;
use App\Exceptions\Handlers\SchemaMismatchExceptionHandler;
use App\Exceptions\Handlers\ValidationExceptionHandler;
use App\Exceptions\Contracts\LoggedException;
use App\Response\JsonErrorResponse;
use App\Util\Logger\Context;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Log\Logger;
use Illuminate\Support\Collection;
use League\OpenAPIValidation\PSR7\Exception\ValidationFailed;
use League\OpenAPIValidation\Schema\Exception\SchemaMismatch;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontReport = [
        //
    ];

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register()
    {
        $this->reportable(function (Throwable $e) {
            if ($e instanceof LoggedException) {
                /** @var Logger $logger */
                $logger = $this->container->make(\Illuminate\Log\Logger::class);

                $logger->{$e->getLogLevel()}(
                    $e->getMessage(),
                    (new Context())->setCode($e->getCode())
                        ->setFile($e->getFile())
                        ->setLine($e->getLine())
                        ->setTraceString($e->getTraceAsString())
                        ->toArray()
                );
            }
        });
    }

    public function render($request, Throwable $e)
    {
        return parent::render($request, $e);
    }

    public function prepareJsonResponse($request, Throwable $e)
    {
        $errors = new Collection();

        if ($e instanceof ValidationException) {
            $code = Response::HTTP_UNPROCESSABLE_ENTITY;
            foreach ($e->getErrors() as $error) {
                $errors->add((new ValidationExceptionHandler($error, $e)));
            }
        }

        if ($e instanceof ValidationFailed) {
            $code = Response::HTTP_BAD_REQUEST;
            if ($e->getPrevious() && $e->getPrevious() instanceof SchemaMismatch) {
                $errors->add((new SchemaMismatchExceptionHandler($e->getPrevious())));
            } else {
                $errors->add((new RequestValidationExceptionHandler($e)));
            }
        }

        if($errors->isEmpty()){
            return parent::prepareJsonResponse($request, $e);
        }

        return new JsonErrorResponse(
            (new DisplayHandler(
                '', $code ?? Response::HTTP_INTERNAL_SERVER_ERROR
            ))->setErrors($errors)
        );
    }
}
