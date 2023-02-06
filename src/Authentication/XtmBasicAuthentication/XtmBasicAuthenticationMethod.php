<?php

declare(strict_types=1);

namespace Opdavies\XtmConnect\Authentication\XtmBasicAuthentication;

use Opdavies\XtmConnect\Authentication\AuthenticationMethodInterface;
use Opdavies\XtmConnect\Authentication\XtmBasicAuthentication\XtmBasicAuthenticationParameters;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Webmozart\Assert\Assert;

final class XtmBasicAuthenticationMethod implements AuthenticationMethodInterface
{
    private XtmBasicAuthenticationParameters $parameters;

    public function __construct(
        private HttpClientInterface $httpClient,
    ) {}

    public function withParameters(XtmBasicAuthenticationParameters $parameters): self
    {
        Assert::stringNotEmpty($parameters->client, 'Missing client');
        Assert::stringNotEmpty($parameters->password, 'Missing password');
        Assert::positiveInteger($parameters->userId, 'Missing user ID');

        $this->parameters = $parameters;

        return $this;
    }

    public function getToken(): string
    {
        $response = $this->httpClient->request('POST', '');
        $responseData = $response->toArray();

        return $responseData['token'];
    }
}
