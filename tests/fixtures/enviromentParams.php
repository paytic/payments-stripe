<?php

$parameters = $parameters ?? [];

$parameters = array_merge(
    $parameters,
    [
        'publicKey' => getenv('STRIPE_PUBLIC_KEY'),
        'apiKey' => getenv('STRIPE_SECRET_KEY'),
        'testMode' => true
    ]
);

return $parameters;
