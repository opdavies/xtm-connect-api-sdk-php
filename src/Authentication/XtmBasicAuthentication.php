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

    private int $userId = 0;

    public function __construct(
        private HttpClientInterface $httpClient,
    ) {}

    public function forClient(string $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function forUser(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getToken(): string
    {
        Assert::stringNotEmpty($this->client, 'Missing client');
        Assert::stringNotEmpty($this->password, 'Missing password');
        Assert::positiveInteger($this->userId, 'Missing user ID');

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
