<?php

namespace Oniric85\UsersService\Service\Infrastructure;

use RuntimeException;

class IpApiClient
{
    /**
     * @throws RuntimeException
     */
    public function getCountryCodeFromIp(string $ip): string
    {
        $response = file_get_contents(sprintf('https://ipapi.co/%s/json/', $ip));

        if (($payload = json_decode($response, true)) === null) {
            throw new RuntimeException('Error with external ipapi service.');
        }

        if (isset($payload['error'])) {
            throw new RuntimeException(sprintf('Error with external ipapi service: %s.', $payload['reason']));
        }

        return $payload['country_code'];
    }
}