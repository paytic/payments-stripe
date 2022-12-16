<?php

declare(strict_types=1);

namespace Paytic\Payments\Stripe\Utility;

/**
 * Class Paths.
 */
class Paths
{
    public static function viewsPath()
    {
        return \dirname(__DIR__, 2) . '/resources/views/';
    }
}
