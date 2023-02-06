<?php

declare(strict_types=1);

namespace Opdavies\XtmConnect\Authentication;

use Opdavies\XtmConnect\Authentication\AuthenticationMethodInterface as AuthenticationAuthenticationMethodInterface;

final class XtmBasicAuthentication implements AuthenticationAuthenticationMethodInterface
{
    public function getToken(): string
    {
        return '';
    }
}
