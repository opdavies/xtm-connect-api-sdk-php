<?php

declare(strict_types=1);

namespace Tests\Opdavies\XtmConnect\Authentication;

use Opdavies\XtmConnect\Authentication\XtmBasicAuthentication;
use PHPUnit\Framework\TestCase;

final class XtmBasicAuthenticationTest extends TestCase
{
    /** @test */
    public function should_return_a_token(): void
    {
        $token = (new XtmBasicAuthentication())
            ->getToken();

        self::assertIsString($token);
    }  
}
