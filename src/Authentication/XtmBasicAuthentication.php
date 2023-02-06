<?php

declare(strict_types=1);

namespace Opdavies\XtmConnect\Authentication;

use Opdavies\XtmConnect\Authentication\AuthenticationMethodInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Webmozart\Assert\Assert;

final class XtmBasicAuthentication implements AuthenticationMethodInterface
{
    private string $client = '';

    public function __construct(
        private HttpClientInterface $httpClient,
    ) {}

    public function forClient(string $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getToken(): string
    {
        Assert::stringNotEmpty($this->client);

        $response = $this->httpClient->request('POST', '');
        $responseData = $response->toArray();

        return $responseData['token'];
    }
}
