<?php

declare(strict_types=1);

namespace Opdavies\XtmConnect\ProjectFiles\UseCase;

use Opdavies\XtmConnect\Authentication\AuthenticationMethodInterface;
use Opdavies\XtmConnect\ProjectFiles\Enum\GeneratedFileScope;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Downloads a single, generated project file based on its ID.
 *
 * The response is generated in the application/stream format as a ZIP file.
 */
final class DownloadProjectFile
{
    public function __construct(
        private string $apiUrl,
        private HttpClientInterface $httpClient,
        private AuthenticationMethodInterface $authenticationMethod,
    ) {
    }

    public function handle(
        int $projectId,
        int $fileId,
        string $fileScope = GeneratedFileScope::JOB,
    ): string {
        $response = $this->httpClient->request(
            options: [
                'query' => [
                    'fileScope' => $fileScope,
                ],
                'headers' => $this->headers(),
            ],
            method: 'GET',
            url: sprintf(
                '%s/projects/%d/files/%d/download',
                $this->apiUrl,
                $projectId,
                $fileId,
            )
        );

        return $response->getContent();
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
