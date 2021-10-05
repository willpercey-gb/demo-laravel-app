<?php

namespace Tests\Feature;

use App\Events\RequestValidationEvent;
use App\Listeners\RequestValidationSubscriber;
use Illuminate\Http\Request;
use Tests\TestCase;

class EventsAreCapturedTest extends TestCase
{
    public function testEventIsTriggeredOnRequest(): void
    {
        $mock = $this->getMockBuilder(RequestValidationSubscriber::class)
            ->disableProxyingToOriginalMethods()
            ->getMock();
        $mock->expects($this->once())->method('__invoke');

        $this->instance(RequestValidationSubscriber::class, $mock);

        event(new RequestValidationEvent(Request::createFromGlobals()));
    }
}
