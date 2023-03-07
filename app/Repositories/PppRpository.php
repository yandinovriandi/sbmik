<?php

namespace App\Repositories;

use App\Models\Mikrotik;
use RouterOS\Client;
use RouterOS\Exceptions\BadCredentialsException;
use RouterOS\Exceptions\ClientException;
use RouterOS\Exceptions\ConfigException;
use RouterOS\Exceptions\ConnectException;
use RouterOS\Exceptions\QueryException;
use RouterOS\Query;

class PppRpository
{
    /**
     * @throws ClientException
     * @throws ConnectException
     * @throws BadCredentialsException
     * @throws QueryException
     * @throws ConfigException
     */
    public function getPppSecret()
    {
        $mikrotik = auth()->user()->mikrotiks()->where('slug',request()->slug);
        $client = new Client([
            'host' => $mikrotik->host,
            'user' => $mikrotik->username,
            'pass' => $mikrotik->password,
            'port' => (int) $mikrotik->port,
        ]);
        $query = new Query('/ppp/secret/print');
        return $client->query($query)->read();
    }

    /**
     * @throws ClientException
     * @throws ConnectException
     * @throws QueryException
     * @throws BadCredentialsException
     * @throws ConfigException
     */
    public function countPppActive(Mikrotik $mikrotik): int
    {
        $client = new Client([
            'host' => $mikrotik->host,
            'user' => $mikrotik->username,
            'pass' => $mikrotik->password,
            'port' => (int) $mikrotik->port,
        ]);
        $query = new Query('/ppp/active/print');
        return count($client->query($query)->read());
    }

    /**
     * @throws ClientException
     * @throws ConnectException
     * @throws BadCredentialsException
     * @throws QueryException
     * @throws ConfigException
     */
    public function countPppSecret(Mikrotik $mikrotik): int
    {
        $client = new Client([
            'host' => $mikrotik->host,
            'user' => $mikrotik->username,
            'pass' => $mikrotik->password,
            'port' => (int) $mikrotik->port,
        ]);
        $query = new Query('/ppp/secret/print');
        return count($client->query($query)->read());
    }
}
