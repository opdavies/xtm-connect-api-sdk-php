<?php

declare(strict_types=1);

namespace Tests\Opdavies\XtmConnect\Authentication;

use InvalidArgumentException;
use Opdavies\XtmConnect\Authentication\XtmBasicAuthentication\XtmBasicAuthenticationMethod;
use Opdavies\XtmConnect\Authentication\XtmBasicAuthentication\XtmBasicAuthenticationParameters;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use PHPUnit\Framework\TestCase;

final class XtmBasicAuthenticationMethodTest extends TestCase
{
    /** @test */
    public function should_return_a_valid_token(): void
    {
        $expectedResponseData = ['token' => 'valid-token'];
        $mockResponseJson = json_encode($expectedResponseData, JSON_THROW_ON_ERROR);
        $mockResponse = new MockResponse($mockResponseJson);

        $httpClient = new MockHttpClient($mockResponse);

        $authenticationParameters = new XtmBasicAuthenticationParameters();
        $authenticationParameters->client = 'company-name';
        $authenticationParameters->password = 'password';
        $authenticationParameters->userId = 123456;

        $token = (new XtmBasicAuthenticationMethod($httpClient))
            ->withParameters($authenticationParameters)
            ->getToken();

        self::assertSame('valid-token', $token);
    }  

    /** @test */
    public function should_throw_an_exception_if_there_is_no_client_specified(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Missing client');

        $expectedResponseData = [];
        $mockResponseJson = json_encode($expectedResponseData, JSON_THROW_ON_ERROR);
        $mockResponse = new MockResponse($mockResponseJson, ['http_code' => 400]);

        $httpClient = new MockHttpClient($mockResponse);

        $authenticationParameters = new XtmBasicAuthenticationParameters();
        $authenticationParameters->password = 'password';
        $authenticationParameters->userId = 123456;

        (new XtmBasicAuthenticationMethod($httpClient))
            ->withParameters($authenticationParameters)
            ->getToken();
    }

    /** @test */
    public function should_throw_an_exception_if_there_is_no_password_specified(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Missing password');

        $expectedResponseData = [];
        $mockResponseJson = json_encode($expectedResponseData, JSON_THROW_ON_ERROR);
        $mockResponse = new MockResponse($mockResponseJson, ['http_code' => 400]);

        $httpClient = new MockHttpClient($mockResponse);

        $authenticationParameters = new XtmBasicAuthenticationParameters();
        $authenticationParameters->client = 'company-name';
        $authenticationParameters->user = 123456;

        (new XtmBasicAuthenticationMethod($httpClient))
            ->withParameters($authenticationParameters)
            ->getToken();
    }

    /** @test */
    public function should_throw_an_exception_if_there_is_no_user_id_specified(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Missing user ID');

        $expectedResponseData = [];
        $mockResponseJson = json_encode($expectedResponseData, JSON_THROW_ON_ERROR);
        $mockResponse = new MockResponse($mockResponseJson, ['http_code' => 400]);

        $httpClient = new MockHttpClient($mockResponse);

        $authenticationParameters = new XtmBasicAuthenticationParameters();
        $authenticationParameters->client = 'company-name';
        $authenticationParameters->password = 'password';

        (new XtmBasicAuthenticationMethod($httpClient))
            ->withParameters($authenticationParameters)
            ->getToken();
    }
}
