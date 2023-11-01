<?php

declare(strict_types=1);

namespace Conia\Wire\Tests;

use Conia\Wire\Call;
use Conia\Wire\Exception\WireException;
use Conia\Wire\Resolver;
use Conia\Wire\Tests\Fixtures\TestClassApp;
use Conia\Wire\Tests\Fixtures\TestClassCall;
use Conia\Wire\Tests\Fixtures\TestClassInject;
use Conia\Wire\Tests\Fixtures\TestClassRequest;
use Conia\Wire\Tests\Fixtures\TestContainer;
use Conia\Wire\Tests\TestCase;

final class CallTest extends TestCase
{
    public function testCallAttributes(): void
    {
        $resolver = new Resolver($this->container());
        $attr = $resolver->resolve(TestClassCall::class);

        $this->assertInstanceOf(TestContainer::class, $attr->container);
        $this->assertInstanceOf(TestClassApp::class, $attr->app);
        $this->assertInstanceOf(TestClassRequest::class, $attr->request);
        $this->assertEquals('arg1', $attr->arg1);
        $this->assertEquals('arg2', $attr->arg2);
    }

    public function testCallAttributeDoesNotAllowUnnamedArgs(): void
    {
        $this->throws(WireException::class, 'Arguments for Call');

        new Call('method', 'arg');
    }
}