<?php

declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';

use Conia\Wire\Resolver;

class Value
{
    public function get(): string
    {
        return 'From autowired Value instance';
    }
}

class Model
{
    public function __construct(protected Value $valueObj)
    {
    }

    public function value(): string
    {
        return $this->valueObj->get();
    }
}

$resolver = new Resolver();
$object = $resolver->create(Model::class);

assert($object->value() === 'From autowired Value instance');
