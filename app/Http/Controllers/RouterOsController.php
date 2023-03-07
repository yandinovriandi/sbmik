<?php

namespace App\Http\Controllers;

use App\Models\Mikrotik;
use App\Repositories\PppRpository;
use App\Repositories\HotspotRepository;
use RouterOS\Exceptions\BadCredentialsException;
use RouterOS\Exceptions\ClientException;
use RouterOS\Exceptions\ConfigException;
use RouterOS\Exceptions\ConnectException;
use RouterOS\Exceptions\QueryException;

class RouterOsController extends Controller
{
    private HotspotRepository $hotspotRepository;
    private PppRpository $pppRepository;

    public function __construct(
        HotspotRepository $hotspotRepository,
        PppRpository $pppRpository
    )
    {
        $this->hotspotRepository = $hotspotRepository;
        $this->pppRepository = $pppRpository;
    }

    /**
     * @throws ClientException
     * @throws ConnectException
     * @throws BadCredentialsException
     * @throws QueryException
     * @throws ConfigException
     */
    public function getProfileHotspot()
    {
       return $this->hotspotRepository->getProfiles();
    }

    /**
     * @throws ClientException
     * @throws ConnectException
     * @throws QueryException
     * @throws BadCredentialsException
     * @throws ConfigException
     */
//    count user hotspot online
    public function hotspotActive()
    {
        return $this->hotspotRepository->countHotspotActive();
    }

//    count hotspot users

    /**
     * @throws ClientException
     * @throws ConnectException
     * @throws BadCredentialsException
     * @throws QueryException
     * @throws ConfigException
     */
    public function hotspotUser()
    {
        return $this->hotspotRepository->countHotspotUser();
    }

//    count ppp secret

    /**
     * @throws ClientException
     * @throws ConnectException
     * @throws QueryException
     * @throws BadCredentialsException
     * @throws ConfigException
     */
    public function pppSecret(Mikrotik $mikrotik)
    {
        return $this->pppRepository->countPppSecret($mikrotik);
    }

//    count ppp active

    /**
     * @throws ClientException
     * @throws ConnectException
     * @throws BadCredentialsException
     * @throws QueryException
     * @throws ConfigException
     */
    public function pppActive(Mikrotik $mikrotik)
    {
        return $this->pppRepository->countPppActive($mikrotik);
    }
}
