<?php

declare(strict_types=1);

namespace Opdavies\XtmConnect\Authentication;

interface AuthenticationMethodInterface
{
    public function getToken(): string;
}
