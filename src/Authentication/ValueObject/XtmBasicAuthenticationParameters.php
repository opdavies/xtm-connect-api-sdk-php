<?php

declare(strict_types=1);

namespace Opdavies\XtmConnect\Authentication\ValueObject;

/**
 * Parameters needed to authenticate using XTM Basic authentication.
 */
final class XtmBasicAuthenticationParameters
{
    public int $userId = 0;
    public string $client = '';
    public string $password = '';
}
