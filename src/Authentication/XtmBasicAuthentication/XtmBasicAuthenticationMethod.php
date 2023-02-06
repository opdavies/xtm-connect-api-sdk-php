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
        private string $apiUrl,
        private HttpClientInterface $httpClient,
    ) {
    }

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
        $endpointPath = 'auth/token';
        $endpointUrl = sprintf('%s/%s', $this->apiUrl, $endpointPath);

        $response = $this->httpClient->request('POST', $endpointUrl, [
            'json' => [
                'client' => $this->parameters->client,
                'password' => $this->parameters->password,
                'userId' => $this->parameters->userId,
            ],
        ]);

        $responseData = $response->toArray();

        return $responseData['token'];
    }
}
