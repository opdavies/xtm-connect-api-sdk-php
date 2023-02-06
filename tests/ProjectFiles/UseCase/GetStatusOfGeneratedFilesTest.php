<?php

declare(strict_types=1);

namespace Tests\Opdavies\XtmConnect\ProjectFiles\UseCase;

use Opdavies\XtmConnect\Authentication\AuthenticationMethodInterface;
use Opdavies\XtmConnect\ProjectFiles\Enum\GeneratedFileStatus;
use Opdavies\XtmConnect\ProjectFiles\UseCase\GetStatusOfGeneratedFiles;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class GetStatusOfGeneratedFilesTest extends TestCase
{
    /** @test */
    public function should_retrieve_the_generated_files_for_a_project(): void
    {
        $mockAuthenticationMethod = $this->createMock(AuthenticationMethodInterface::class);
        $mockHttpClient = $this->createMock(HttpClientInterface::class);
        $mockResponse = $this->createMock(ResponseInterface::class);

        $mockResponseData = [
            [
                'fileId' => 2222,
                'projectId' => 1111,
                'status' => GeneratedFileStatus::FINISHED,
            ],
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

        $useCase = new GetStatusOfGeneratedFiles(
            authenticationMethod: $mockAuthenticationMethod,
            apiUrl: 'http://test.com',
            httpClient: $mockHttpClient,
        );

        $useCase->handle(
            projectId: 1111,
        );

        self::assertSame(1111, $mockResponseData[0]['projectId']);
    }
}
