<?php

declare(strict_types=1);

namespace Opdavies\XtmConnect\Authentication\XtmBasicAuthentication;

/**
 * Parameters needed to authenticate using XTM Basic authentication.
 */
final class XtmBasicAuthenticationParameters
{
    public int $userId = 0;
    public string $client = '';
    public string $password = '';
}
