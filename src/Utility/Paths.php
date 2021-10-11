<?php

namespace Paytic\Payments\Stripe\Utility;

/**
 * Class Paths
 * @package Paytic\Payments\Stripe\Utility
 */
class Paths
{
    public static function viewsPath()
    {
        return dirname(dirname(__DIR__)) . '/resources/views/';
    }
}
