<?php

declare(strict_types=1);

namespace Opdavies\XtmConnect\ProjectFiles\UseCase;

use Opdavies\XtmConnect\Authentication\AuthenticationMethodInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class GetStatusOfGeneratedFiles {

    public function __construct(
        private $apiUrl,
        private HttpClientInterface $httpClient,
        private AuthenticationMethodInterface $authenticationMethod,
    ) {
    }

    public function handle(int $projectId)
    {
        $response = $this->httpClient->request(
            options: [
                'query' => [
                    'fileScope' => 'PROJECT',
                    'fileType' => 'TARGET',
                ],
                'headers' => $this->headers(),
            ],
            method: 'GET',
            url: sprintf('%s/projects/%d/files/status',
                $this->apiUrl,
                $projectId,
            )
        );

        return $response->toArray();
    }

    /**
     * @return array<int, string>
     */
    private function headers(): array
    {
        $token = $this->authenticationMethod->getToken();

        $headers = [];
        $headers[] = "Authorization: XTM-Basic ${token}";

        return $headers;
    }
}
