<?php

declare(strict_types=1);

namespace Conia\Wire\Tests\Fixtures;

class TestClassDefault
{
    public function __construct(
        public readonly int $number,
        public readonly TestClass $tc,
        public readonly string $name = 'default',
    ) {
    }
}
