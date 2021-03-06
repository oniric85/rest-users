<?php

namespace Oniric85\UsersService\Service\Infrastructure;

use RuntimeException;

class IpApiClient implements IpApiClientInterface
{
    /**
     * @throws RuntimeException
     */
    public function getCountryCodeFromIp(string $ip): string
    {
        $response = file_get_contents(sprintf('https://ipapi.co/%s/json/', $ip));

        if (null === ($payload = json_decode($response, true))) {
            throw new RuntimeException('Error with external ipapi service.');
        }

        if (isset($payload['error'])) {
            throw new RuntimeException(sprintf('Error with external ipapi service: %s.', $payload['reason']));
        }

        return $payload['country_code'];
    }
}
