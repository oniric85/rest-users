<?php

namespace Oniric85\UsersService\Service\Infrastructure;

interface IpApiClientInterface
{
    public function getCountryCodeFromIp(string $ip): string;
}