<?php

declare(strict_types=1);

$parameters ??= [];

$parameters = array_merge(
    $parameters,
    [
        'publicKey' => getenv('STRIPE_PUBLIC_KEY'),
        'apiKey' => getenv('STRIPE_SECRET_KEY'),
        'testMode' => true,
    ]
);

return $parameters;
