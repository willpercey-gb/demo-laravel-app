<?php

namespace App\Listeners;

use App\Events\RequestValidationEvent;
use Illuminate\Events\Dispatcher;
use League\OpenAPIValidation\PSR7\Exception\NoPath;
use League\OpenAPIValidation\PSR7\PathFinder;
use League\OpenAPIValidation\PSR7\ValidatorBuilder;
use Nyholm\Psr7\Factory\Psr17Factory;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;

class RequestValidationSubscriber
{
    public function __invoke(RequestValidationEvent $event): void
    {
        $request = $event->getRequest();

        $validator = (new ValidatorBuilder())
            ->fromYamlFile(base_path('/openapi/phonebook-api.yaml'))
            ->getRoutedRequestValidator();

        $openApiSpec = $validator->getSchema();

        $path = (new PathFinder($openApiSpec, $request->getPathInfo(), $request->getMethod()))
            ->search();

        if (count($path) < 1) {
            throw (new NoPath());
        }

        $address = $path[0];

        $psr17Factory = new Psr17Factory();
        $psrHttpFactory = new PsrHttpFactory($psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory);
        $psrRequest = $psrHttpFactory->createRequest($request);

        $validator->validate($address, $psrRequest);
    }

    public function subscribe(Dispatcher $events)
    {
        $events->listen(
            RequestValidationEvent::class,
            self::class
        );
    }
}
