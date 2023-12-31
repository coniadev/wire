<?php

declare(strict_types=1);

namespace Conia\Wire;

use ReflectionClass;

/** @psalm-api */
class ConstructorResolver
{
    use ResolvesAbstractFunctions;

    public function __construct(
        protected readonly CreatorInterface $creator,
    ) {
    }

    /** @psalm-param ReflectionClass|class-string $class */
    public function resolve(
        ReflectionClass|string $class,
        array $predefinedArgs = [],
        array $predefinedTypes = [],
    ): array {
        $rc = is_string($class) ? new ReflectionClass($class) : $class;
        $constructor = $rc->getConstructor();

        if ($constructor) {
            return $this->resolveArgs($constructor, $predefinedArgs, $predefinedTypes);
        }

        return $predefinedArgs;
    }

    public function creator(): CreatorInterface
    {
        return $this->creator;
    }
}
