<?php

namespace ByTIC\Payments\Stripe\Utility;

/**
 * Class Paths
 * @package ByTIC\Payments\Stripe\Utility
 */
class Paths
{
    public static function viewsPath()
    {
        return dirname(dirname(__DIR__)) . '/resources/views/';
    }
}
