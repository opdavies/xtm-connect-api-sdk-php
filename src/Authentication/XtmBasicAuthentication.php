<?php

declare(strict_types=1);

namespace Opdavies\XtmConnect\Authentication;

use Opdavies\XtmConnect\Authentication\AuthenticationMethodInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Webmozart\Assert\Assert;

final class XtmBasicAuthentication implements AuthenticationMethodInterface
{
    private string $client = '';

    private string $password = '';

    private string $user = '';

    public function __construct(
        private HttpClientInterface $httpClient,
    ) {}

    public function forClient(string $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function forUser(string $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getToken(): string
    {
        Assert::stringNotEmpty($this->client, 'Missing client');
        Assert::stringNotEmpty($this->password, 'Missing password');
        Assert::stringNotEmpty($this->user, 'Missing user ID');

        $response = $this->httpClient->request('POST', '');
        $responseData = $response->toArray();

        return $responseData['token'];
    }

    public function withPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }
}
