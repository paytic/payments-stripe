<?php

return [
    'transactionId' => random_int(9999999, 9999999999999999),
    'amount' => 100,
    'description' => 'Transaction test',
    'currency' => 'ron',
    'card' => [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'phone' => '0741000111',
        'country' => 'Romania',
        'state' => 'Bucharest',
        'city' => 'Bucharest',
        'address1' => 'NoStreet',
        'email' => 'john.doe@gmail.com',
    ],
];
