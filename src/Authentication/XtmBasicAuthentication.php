<?php

declare(strict_types=1);

namespace Opdavies\XtmConnect\Authentication;

use Opdavies\XtmConnect\Authentication\AuthenticationMethodInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class XtmBasicAuthentication implements AuthenticationMethodInterface
{
    public function __construct(
        private HttpClientInterface $httpClient,
    ) {}

    public function getToken(): string
    {
        $response = $this->httpClient->request('POST', '');
        $responseData = $response->toArray();

        return $responseData['token'];
    }
}
