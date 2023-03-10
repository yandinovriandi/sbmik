<?php

namespace App\Repositories;



use Illuminate\Support\Facades\Auth;
use RouterOS\Client;
use RouterOS\Config;
use RouterOS\Exceptions\BadCredentialsException;
use RouterOS\Exceptions\ClientException;
use RouterOS\Exceptions\ConfigException;
use RouterOS\Exceptions\ConnectException;
use RouterOS\Exceptions\QueryException;
use RouterOS\Query;

class HotspotRepository
{
    /**
     * @throws ClientException
     * @throws ConnectException
     * @throws BadCredentialsException
     * @throws QueryException
     * @throws ConfigException
     * @throws \Exception
     */
    public function getMikrotik(): Client
    {
        $mikrotik = Auth::user()->mikrotiks()->where('slug', request()->mikrotik)->first();

        if (!$mikrotik) {
            throw new \Exception('Mikrotik not found.');
        }

        $config = (new Config())
            ->set('host', $mikrotik->host)
            ->set('port', (int) $mikrotik->port)
            ->set('pass', $mikrotik->password)
            ->set('user', $mikrotik->username);

        return new Client($config);
    }

    /**
     * @throws ClientException
     * @throws ConnectException
     * @throws QueryException
     * @throws BadCredentialsException
     * @throws ConfigException
     * @throws \Exception
     */
    public function getMikrotikBySlug(): Client
    {
        $mikrotik = Auth::user()->mikrotiks()->where('slug', request()->mikrotik->slug)->first();

        if (!$mikrotik) {
            throw new \Exception('Mikrotik not found.');
        }

        $config = (new Config())
            ->set('host', $mikrotik->host)
            ->set('port', (int) $mikrotik->port)
            ->set('pass', $mikrotik->password)
            ->set('user', $mikrotik->username);

        return new Client($config);
    }

    /**
     * @throws ClientException
     * @throws ConnectException
     * @throws QueryException
     * @throws BadCredentialsException
     * @throws ConfigException
     */
    public function getProfiles()
    {
        $client = $this->getMikrotik();
        $query = new Query('/ip/hotspot/user/profile/print');
        return $client->query($query)->read();
    }


    /**
     * @throws ClientException
     * @throws ConnectException
     * @throws BadCredentialsException
     * @throws QueryException
     * @throws ConfigException
     */
    public function getHotspotUsers()
    {
        $client = $this->getMikrotikBySlug();
        $query = new Query('/ip/hotspot/user/print');
        return $client->query($query)->read();
    }

    /**
     * @throws ClientException
     * @throws ConnectException
     * @throws QueryException
     * @throws BadCredentialsException
     * @throws ConfigException
     */
    public function getHotspotActive()
    {
        $client = $this->getMikrotikBySlug();
        $query = new Query('/ip/hotspot/active/print');
        return $client->query($query)->read();
    }

    /**
     * @throws ClientException
     * @throws ConnectException
     * @throws BadCredentialsException
     * @throws QueryException
     * @throws ConfigException
     */
    public function countHotspotActive(): int
    {
        $client = $this->getMikrotik();
        $query = new Query('/ip/hotspot/active/print');
        return count($client->query($query)->read());
    }

    /**
     * @throws ClientException
     * @throws ConnectException
     * @throws QueryException
     * @throws BadCredentialsException
     * @throws ConfigException
     */
    public function countHotspotUser(): int
    {
        $client = $this->getMikrotik();
        $query = new Query('/ip/hotspot/user/print');
        return count($client->query($query)->read());
    }

    /**
     * @throws ClientException
     * @throws ConnectException
     * @throws QueryException
     * @throws BadCredentialsException
     * @throws ConfigException
     */
    public function addUserProfile(
        $name,$addrpool,$ratelimit,$sharedusers,$onlogin,$parent,$validity
    )

    {
        $client = $this->getMikrotik();
        $query = new Query('/ip/hotspot/user/profile/add');
        $query->equal('name', $name);
        $query->equal('address-pool',$addrpool);
        $query->equal('shared-users', $sharedusers);
        $query->equal('session-timeout', $validity);
        $query->equal('keepalive-timeout', $validity);
        $query->equal('rate-limit', $ratelimit);
        $query->equal('status-autorefresh', '1m');
        $query->equal('on-login', $onlogin);
        $query->equal('parent-queue', $parent);
        return $client->query($query)->read();
    }

    /**
     * @throws ClientException
     * @throws ConnectException
     * @throws BadCredentialsException
     * @throws QueryException
     * @throws ConfigException
     */
    public function getUserProfile()
    {
        $client = $this->getMikrotikBySlug();
        $query = new Query('/ip/hotspot/user/profile/print');
        return $client->query($query)->read();
    }

    /**
     * @throws ClientException
     * @throws ConnectException
     * @throws BadCredentialsException
     * @throws QueryException
     * @throws ConfigException
     */
    public function removeUserProfile($pname)
    {
        $client = $this->getMikrotikBySlug();
        $query = new Query('/ip/hotspot/user/profile/remove');
        $query->equal('name',$pname);
        return $client->query($query)->read();
    }
    /**
     * @throws ClientException
     * @throws ConnectException
     * @throws QueryException
     * @throws BadCredentialsException
     * @throws ConfigException
     */
    public function addScheduler($name, $randstarttime, $randinterval, $bgservice)
    {
        $client = $this->getMikrotik();
        $query = new Query('/system/scheduler/add');
        $query->equal('name', $name);
        $query->equal('start-time', $randstarttime);
        $query->equal('interval', $randinterval);
        $query->equal('on-event', $bgservice);
        $query->equal('disabled', 'no');
        $query->equal('comment', 'Monitor Profile ' . $name);
        return $client->query($query)->read();
    }

    /**
     * @throws ClientException
     * @throws ConnectException
     * @throws QueryException
     * @throws BadCredentialsException
     * @throws ConfigException
     */
    public function setScheduler($monid, $name, $randstarttime, $randinterval, $bgservice)
    {
        $client = $this->getMikrotik();
        $query = new Query('/system/scheduler/set');
        $query->where('.id', $monid);
        $query->equal('.id', $monid);
        $query->equal('name', $name);
        $query->equal('start-time', $randstarttime);
        $query->equal('interval', $randinterval);
        $query->equal('on-event', $bgservice);
        $query->equal('disabled', 'no');
        $query->equal('comment', 'Monitor Profile ' . $name);
        return $client->query($query)->read();
    }

    /**
     * @throws ClientException
     * @throws ConnectException
     * @throws QueryException
     * @throws BadCredentialsException
     * @throws ConfigException
     */
    public function removeScheduller($monid)
    {
        $client = $this->getMikrotik();
        $query = new Query('/system/scheduler/remove');
        $query->equal('.id', $monid);
        return $client->query($query)->read();
    }
    /**
     * @throws ClientException
     * @throws ConnectException
     * @throws QueryException
     * @throws BadCredentialsException
     * @throws ConfigException
     */
    public function getSchedulerByHotspotpName( $name)
    {
        $client = $this->getMikrotik();
        $query = new Query('/system/scheduler/print');
        $query->where('name', $name);
        return $client->query($query)->read();
    }
    /**
     * @throws ClientException
     * @throws ConnectException
     * @throws BadCredentialsException
     * @throws QueryException
     * @throws ConfigException
     */
    public function getSimpleQueue()
    {
        $client = $this->getMikrotikBySlug();
        $query = new Query('/queue/simple/print');
        $query->where('dynamic', 'no');
        return $client->query($query)->read();
    }

    /**
     * @throws ClientException
     * @throws ConnectException
     * @throws QueryException
     * @throws BadCredentialsException
     * @throws ConfigException
     */
    public function getIpPool()
    {
        $client = $this->getMikrotikBySlug();
        $query = new Query('/ip/pool/print');
        return $client->query($query)->read();
    }

    /**
     * @throws ClientException
     * @throws ConnectException
     * @throws QueryException
     * @throws BadCredentialsException
     * @throws ConfigException
     */
    public function getRateLimit(mixed $name)
    {
        $this->getUserProfile();
    }

}
