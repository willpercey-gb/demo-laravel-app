<?php

namespace App\Http\Controllers\V1;

use App\Events\RequestValidationEvent;
use App\Http\Controllers\Controller;
use App\Response\Factory;
use Illuminate\Http\Request;
use Neomerx\JsonApi\Contracts\Encoder\EncoderInterface;
use Neomerx\JsonApi\Contracts\Schema\BaseLinkInterface;
use Neomerx\JsonApi\Schema\Link;

class AbstractController extends Controller
{
    protected EncoderInterface $encoder;

    public function __construct(Request $request)
    {
        $this->encoder = Factory::instance()->withLinks(
            [
                BaseLinkInterface::SELF => new Link(true, $request->getPathInfo(), false)
            ]
        )->withIncludedPaths(
            $this->getIncluded($request)
        );

        event(new RequestValidationEvent($request));
    }

    private function getIncluded(Request $request): array
    {
        return !empty($request->get('include')) ? explode(',', $request->get('include')) : [];
    }
}
