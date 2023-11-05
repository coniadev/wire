<?php

declare(strict_types=1);

namespace Conia\Wire;

use Conia\Wire\Exception\WireException;
use ReflectionFunctionAbstract;
use ReflectionNamedType;
use ReflectionParameter;

/** @psalm-api */
trait ResolvesAbstractFunctions
{
    abstract public function creator(): CreatorInterface;

    public function resolveArgs(
        ReflectionFunctionAbstract $rf,
        array $predefinedArgs = [],
    ): array {
        $combinedArgs = array_merge($this->resolveInjectedArgs($rf), $predefinedArgs);

        $args = [];
        $parameters = $rf->getParameters();
        $countPredefined = count($combinedArgs);

        if (array_is_list($combinedArgs) && $countPredefined > 0) {
            // predefined args are not named, use them as they are
            $args = $combinedArgs;
            $parameters = array_slice($parameters, $countPredefined);
        }

        foreach ($parameters as $param) {
            $name = $param->getName();

            if (isset($combinedArgs[$name])) {
                /** @psalm-var list<mixed> */
                $args[] = $combinedArgs[$name];
            } else {
                /** @psalm-var list<mixed> */
                $args[] = $this->resolveParam($param);
            }
        }

        return $args;
    }

    protected function resolveParam(ReflectionParameter $param): mixed
    {
        $type = $param->getType();

        if ($type instanceof ReflectionNamedType) {
            $creator = $this->creator();
            $container = $creator->container();
            $typeName = ltrim($type->getName(), '?');

            if ($container?->has($typeName)) {
                return $container->get($typeName);
            }

            if (class_exists($typeName)) {
                return $creator->create($typeName);
            }

            if ($param->isDefaultValueAvailable()) {
                return $param->getDefaultValue();
            }

            throw new WireException('Parameter not resolvable');
        }

        if ($type) {
            throw new WireException(
                "Cannot resolve union or intersection types. Source: \n" .
                    ParameterInfo::info($param)
            );
        }

        throw new WireException(
            "To be resolvable, classes must have fully typed constructor parameters. Source: \n" .
                ParameterInfo::info($param)
        );
    }

    protected function resolveInjectedArgs(ReflectionFunctionAbstract $rf): array
    {
        /** @psalm-var array<non-empty-string, mixed> */
        $result = [];
        $injectAttrs = $rf->getAttributes(Inject::class);

        foreach ($injectAttrs as $injectAttr) {
            $instance = $injectAttr->newInstance();

            /** @psalm-suppress MixedAssignment */
            foreach ($instance->args as $name => $value) {
                assert(is_string($name));

                if (is_string($value)) {
                    $creator = $this->creator();
                    $container = $creator->container();

                    if ($container?->has($value)) {
                        $result[$name] = $container->get($value);
                    } elseif (class_exists($value)) {
                        $result[$name] = $creator->create($value);
                    } else {
                        $result[$name] = $value;
                    }
                } else {
                    $result[$name] = $value;
                }
            }
        }

        return $result;
    }
}
