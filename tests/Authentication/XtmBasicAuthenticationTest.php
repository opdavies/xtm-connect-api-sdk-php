<?php

declare(strict_types=1);

namespace Tests\Opdavies\XtmConnect\Authentication;

use InvalidArgumentException;
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

        $xtmBasicAuthentication = (new XtmBasicAuthentication($httpClient))
            ->forClient('client-name')
            ->withPassword('password');

        self::assertSame('valid-token', $xtmBasicAuthentication->getToken());
    }  

    /** @test */
    public function should_throw_an_exception_if_there_is_no_client_specified(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $expectedResponseData = [];
        $mockResponseJson = json_encode($expectedResponseData, JSON_THROW_ON_ERROR);
        $mockResponse = new MockResponse($mockResponseJson, ['http_code' => 400]);

        $httpClient = new MockHttpClient($mockResponse);

        (new XtmBasicAuthentication($httpClient))->getToken();
    }

    /** @test */
    public function should_throw_an_exception_if_there_is_no_password_specified(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $expectedResponseData = [];
        $mockResponseJson = json_encode($expectedResponseData, JSON_THROW_ON_ERROR);
        $mockResponse = new MockResponse($mockResponseJson, ['http_code' => 400]);

        $httpClient = new MockHttpClient($mockResponse);

        (new XtmBasicAuthentication($httpClient))
            ->forClient('client-name')
            ->getToken();
    }
}
