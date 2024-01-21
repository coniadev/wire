<?php

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace,PSR1.Files.SideEffects.FoundWithSymbols,PSR1.Classes.ClassDeclaration.MultipleClasses

declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';

use Conia\Wire\Wire;

class Value
{
    public string $str = 'value property';
}

class Model
{
    public function __construct(public Value $value)
    {
    }
}

$resolver = Wire::constructorResolver();
$args = $resolver->resolve(Model::class);

assert((new Model(...$args))->value->str === 'value property');
