<?php

declare(strict_types=1);

namespace Tests\Opdavies\XtmConnect\Authentication;

use Opdavies\XtmConnect\Authentication\XtmBasicAuthentication;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use PHPUnit\Framework\TestCase;

final class XtmBasicAuthenticationTest extends TestCase
{
    /** @test */
    public function should_return_a_valid_token(): void
    {
        $expectedResponseData = ['token' => 'valid-token'];
        $mockResponseJson = json_encode($expectedResponseData, JSON_THROW_ON_ERROR);
        $mockResponse = new MockResponse($mockResponseJson);

        $httpClient = new MockHttpClient($mockResponse);

        $xtmBasicAuthentication = new XtmBasicAuthentication($httpClient);

        self::assertSame('valid-token', $xtmBasicAuthentication->getToken());
    }  
}
