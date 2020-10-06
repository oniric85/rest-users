<?php

namespace Oniric85\UsersService\Service\Infrastructure;

class MockIpApiClient implements IpApiClientInterface
{
    /**
     * Always return Switzerland for tests.
     */
    public function getCountryCodeFromIp(string $ip): string
    {
        return 'CH';
    }
}
