<?php

declare(strict_types=1);

namespace Opdavies\XtmConnect\Authentication;

use Opdavies\XtmConnect\Authentication\AuthenticationMethodInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Webmozart\Assert\Assert;

final class XtmBasicAuthentication implements AuthenticationMethodInterface
{
    private string $clientId = '';

    public function __construct(
        private HttpClientInterface $httpClient,
    ) {}

    public function forClientId(string $clientId): self
    {
        $this->clientId = $clientId;

        return $this;
    }

    public function getToken(): string
    {
        Assert::stringNotEmpty($this->clientId);

        $response = $this->httpClient->request('POST', '');
        $responseData = $response->toArray();

        return $responseData['token'];
    }
}
