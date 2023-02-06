<?php

declare(strict_types=1);

namespace Opdavies\XtmConnect\ProjectFiles\UseCase;

use Opdavies\XtmConnect\Authentication\AuthenticationMethodInterface;
use Symfony\Component\Mime\Part\AbstractPart;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\Multipart\FormDataPart;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class UploadSourceFiles
{
    public function __construct(
        private string $apiUrl,
        private HttpClientInterface $httpClient,
        private AuthenticationMethodInterface $authenticationMethod,
    ) {
    }

    public function handle(
        string $filePath,
        int $projectId,
        string $matchType = 'MATCH_NAMES',
        string $reanalyzeProject = 'NO',
    ): \stdClass {
        $formFields = [
            'files[0].file' => DataPart::fromPath($filePath),
            'matchType' => $matchType,
            'reanalyzeProject' => $reanalyzeProject,
        ];

        $formData = new FormDataPart($formFields);

        $response = $this->httpClient->request(
            options: [
                'body' => $formData->bodyToIterable(),
                'headers' => $this->headers($formData),
            ],
            method: 'POST',
            url: $this->endpointUrl($projectId),
        );

        return (object) $response->toArray();
    }

    private function endpointUrl(int $projectId): string
    {
        return sprintf('%s/%s', $this->apiUrl, "projects/${projectId}/files/sources/upload");
    }

    /**
     * @return array<int, string>
     */
    private function headers(AbstractPart $formData): array
    {
        $token = $this->authenticationMethod->getToken();

        $headers = $formData->getPreparedHeaders()->toArray();
        $headers[] = "Authorization: XTM-Basic ${token}";

        return $headers;
    }
}
