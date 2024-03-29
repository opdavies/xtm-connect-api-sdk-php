<?php

declare(strict_types=1);

namespace Opdavies\XtmConnect\ProjectFiles\UseCase;

use Opdavies\XtmConnect\Authentication\AuthenticationMethodInterface;
use Opdavies\XtmConnect\ProjectFiles\DataTransferObject\GeneratedFile;
use Opdavies\XtmConnect\ProjectFiles\Enum\GeneratedFileScope;
use Opdavies\XtmConnect\ProjectFiles\Enum\GeneratedFileType;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Returns information about the status of generated files.
 */
final class GetStatusOfGeneratedFiles
{
    public function __construct(
        private string $apiUrl,
        private HttpClientInterface $httpClient,
        private AuthenticationMethodInterface $authenticationMethod,
    ) {
    }

    /**
     * @param positive-int $projectId
     * @param array<int, positive-int> $jobIds
     *
     * @return array<int, GeneratedFile>
     */
    public function handle(
        int $projectId,
        string $fileScope = GeneratedFileScope::JOB,
        string $fileType = GeneratedFileType::TARGET,
        array $jobIds = [],
    ): array {
        $response = $this->httpClient->request(
            options: [
                'query' => [
                    'jobIds' => $jobIds,
                    'fileScope' => $fileScope,
                    'fileType' => $fileType,
                ],
                'headers' => $this->headers(),
            ],
            method: 'GET',
            url: sprintf(
                '%s/projects/%d/files/status',
                $this->apiUrl,
                $projectId,
            )
        );

        // TODO: use a serializer?
        return array_map(
            function (array $item) use ($fileScope): GeneratedFile {
                $generatedFile = new GeneratedFile();
                $generatedFile->fileId = $item['fileId'];
                $generatedFile->status = $item['status'];

                if (GeneratedFileScope::JOB === $fileScope) {
                    $generatedFile->jobId = $item['jobId'];
                }

                if (GeneratedFileScope::PROJECT === $fileScope) {
                    $generatedFile->projectId = $item['projectId'];
                }

                return $generatedFile;
            },
            $response->toArray(),
        );
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
