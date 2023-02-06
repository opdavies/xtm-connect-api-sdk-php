<?php

declare(strict_types=1);

namespace Opdavies\XtmConnect\ProjectFiles\UseCase;

use Opdavies\XtmConnect\Authentication\AuthenticationMethodInterface;
use Opdavies\XtmConnect\ProjectFiles\Enum\GeneratedFileScope;
use Opdavies\XtmConnect\ProjectFiles\Enum\GeneratedFileType;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class GetStatusOfGeneratedFiles {

    public function __construct(
        private string $apiUrl,
        private HttpClientInterface $httpClient,
        private AuthenticationMethodInterface $authenticationMethod,
    ) {
    }

    /**
     * @return array<int, array{ fileId: int, projectId: int, status: string }>
     */
    public function handle(
        int $projectId,
        string $fileScope = GeneratedFileScope::JOB,
        string $fileType = GeneratedFileType::TARGET,
    ): array
    {
        $response = $this->httpClient->request(
            options: [
                'query' => [
                    'fileScope' => $fileScope,
                    'fileType' => $fileType,
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