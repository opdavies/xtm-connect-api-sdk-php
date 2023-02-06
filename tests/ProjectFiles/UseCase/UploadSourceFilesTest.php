<?php

declare(strict_types=1);

namespace Tests\Opdavies\XtmConnect\ProjectFiles\UseCase;

use Opdavies\XtmConnect\Authentication\AuthenticationMethodInterface;
use Opdavies\XtmConnect\ProjectFiles\UseCase\UploadSourceFiles;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class UploadSourceFilesTest extends TestCase
{
    /** @test */
    public function should_upload_a_file_and_return_the_jobs(): void
    {
        $mockAuthenticationMethod = $this->createMock(AuthenticationMethodInterface::class);
        $mockHttpClient = $this->createMock(HttpClientInterface::class);
        $mockResponse = $this->createMock(ResponseInterface::class);

        $mockResponseData = [
            'jobs' => [
                [
                    'fileName' => 'example.json',
                    'jobId' => 5678,
                    'sourceFileId' => 9999,
                    'sourceLanguage' => 'en_GB',
                    'targetLanguage' => 'cy_GB',
                ],
            ],
            'name' => 'test',
            'projectId' => 1234,
        ];

        $mockResponse
            ->method('toArray')
            ->willReturn($mockResponseData);

        $mockHttpClient
            ->expects(self::once())
            ->method('request')
            ->willReturn($mockResponse);

        $mockAuthenticationMethod
            ->method('getToken')
            ->willReturn('valid-token');

        $useCase = new UploadSourceFiles(
            authenticationMethod: $mockAuthenticationMethod,
            httpClient: $mockHttpClient,
        );

        $response = $useCase->handle(
            filePath: 'example.json',
            projectId: 1234
        );

        self::assertObjectHasAttribute('projectId', $response);
        self::assertSame(1234, $response->projectId);

        self::assertObjectHasAttribute('jobs', $response);
        self::assertIsArray($response->jobs);
        self::assertNotEmpty($response->jobs);

        self::assertArrayHasKey('fileName', $response->jobs[0]);
        self::assertArrayHasKey('jobId', $response->jobs[0]);
        self::assertArrayHasKey('sourceFileId', $response->jobs[0]);
        self::assertArrayHasKey('sourceLanguage', $response->jobs[0]);
        self::assertArrayHasKey('targetLanguage', $response->jobs[0]);

        self::assertSame('example.json', $response->jobs[0]['fileName']);
    }
}
