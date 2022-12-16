<?php

declare(strict_types=1);

namespace Paytic\Payments\Stripe\Tests;

use Omnipay\Common\Http\Client;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

/**
 * Class AbstractTest.
 */
abstract class AbstractTest extends TestCase
{
    protected $object;

    /**
     * @var \Guzzle\Http\Client
     */
    protected $client;

    /**
     * @return HttpRequest
     */
    protected function generateRequestFromFixtures($path)
    {
        $httpRequest = HttpRequest::createFromGlobals();
        $parameters = require $path;

        $httpRequest->query->replace($parameters['GET'] ?? []);
        $httpRequest->request->replace($parameters['POST'] ?? []);

        return $httpRequest;
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = new Client();
//        $this->client->setConfig(
//            [
//                'curl.CURLOPT_SSL_VERIFYHOST' => false,
//                'curl.CURLOPT_SSL_VERIFYPEER' => false
//            ]
//        );
//        $this->client->setSslVerification(false, false);
    }
}
