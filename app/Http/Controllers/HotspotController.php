<?php

namespace App\Http\Controllers;

use App\Models\Mikrotik;
use App\Repositories\HotspotRepository;
use RouterOS\Exceptions\BadCredentialsException;
use RouterOS\Exceptions\ClientException;
use RouterOS\Exceptions\ConfigException;
use RouterOS\Exceptions\ConnectException;
use RouterOS\Exceptions\QueryException;
use Yajra\DataTables\Facades\DataTables;

class HotspotController extends Controller
{
    private HotspotRepository $hotspotRepository;

    public function __construct(
        HotspotRepository $hotspotRepository
    )
    {
        $this->hotspotRepository = $hotspotRepository;
    }


    /**
     * @throws ClientException
     * @throws ConnectException
     * @throws QueryException
     * @throws BadCredentialsException
     * @throws ConfigException
     */
    public function index(Mikrotik $mikrotik)
    {
        $data = $this->hotspotRepository->getHotspotActive();

        if (request()->ajax()){
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('user', function ($data) {
                    return '<span class="badge badge-info" data-toggle="tooltip" data-original-title="Username">'.$data['user'].'</span>';
                })->editColumn('server', function ($data) {
                    return '<span class="badge badge-primary" data-toggle="tooltip" data-original-title="Server">'.$data['server'].'</span>';
                })
                ->addColumn('session_time_left', function($row) {
                    return $row->session_time_left ?? 'N/A';
                })->addColumn('action',function ($data){
                    return view('mikrotik.hotspot.partials._action')->with('data', $data);
                })->rawColumns(['user','action','server'])
                ->make(true);
        }
        $pools = $this->hotspotRepository->getIpPool();
        $simplequeue = $this->hotspotRepository->getSimpleQueue();
        return view('mikrotik.hotspot.index',[
            'mikrotik' => $mikrotik,
            'pools' => $pools,
            'simplequeue' => $simplequeue
        ]);
    }

    /**
     * @throws ClientException
     * @throws ConnectException
     * @throws BadCredentialsException
     * @throws QueryException
     * @throws ConfigException
     */
    public function createUserProfile()
    {
        $hpName = request()->name;
        $name = str_replace(' ', '', $hpName);
        $hsPool = request()->address_pool;
        $sharedUsers = request()->shared_users;
        $validity = request()->validity;
        $speedLimit = request()->rate_limit;
        $getsprice = request()->selling_price;
        $getprice = request()->price;
        $lockMac = request()->lock_user;
        $parent = request()->parent;
        $expiredMode = request()->expired_mode;

        if ($getprice == "") {
            $price = "0";
        } else {
            $price = $getprice;
        }

        if ($lockMac == "Enable") {
            $lock = '; [:local mac $"mac-address"; /ip hotspot user set mac-address=$mac [find where name=$user]]';
        } else {
            $lock = "";
        }
        $randstarttime = "0".rand(1,5).":".rand(10,59).":".rand(10,59);
        $randinterval = "00:02:".rand(10,59);

        $record = '; :local mac $"mac-address";
        :local time [/system clock get time ];
        /system script add name="$date-|-$time-|-$user-|-'.$price.'-|-$address-|-$mac-|-' . $validity . '-|-'.$name.'-|-$comment" owner="$month$year" source=$date comment=mikhmon';

        $onlogin = ':put (",'.$expiredMode.',' . $price . ',' . $validity . ','.$getsprice.',,' . $lockMac . ',"); {:local date [ /system clock get date ];:local year [ :pick $date 7 11 ];:local month [ :pick $date 0 3 ];:local comment [ /ip hotspot user get [/ip hotspot user find where name="$user"] comment]; :local ucode [:pic $comment 0 2]; :if ($ucode = "vc" or $ucode = "up" or $comment = "") do={ /sys sch add name="$user" disable=no start-date=$date interval="' . $validity . '"; :delay 2s; :local exp [ /sys sch get [ /sys sch find where name="$user" ] next-run]; :local getxp [len $exp]; :if ($getxp = 15) do={ :local d [:pic $exp 0 6]; :local t [:pic $exp 7 16]; :local s ("/"); :local exp ("$d$s$year $t"); /ip hotspot user set comment=$exp [find where name="$user"];}; :if ($getxp = 8) do={ /ip hotspot user set comment="$date $exp" [find where name="$user"];}; :if ($getxp > 15) do={ /ip hotspot user set comment=$exp [find where name="$user"];}; /sys sch remove [find where name="$user"]';
        $mode = "";
        if ($expiredMode == "rem") {
            $onlogin = $onlogin . $lock . "}}";
            $mode = "remove";
        } elseif ($expiredMode == "ntf") {
            $onlogin = $onlogin . $lock . "}}";
            $mode = "set limit-uptime=1s";
        } elseif ($expiredMode == "remc") {
            $onlogin = $onlogin . $record . $lock . "}}";
            $mode = "remove";
        } elseif ($expiredMode == "ntfc") {
            $onlogin = $onlogin . $record . $lock . "}}";
            $mode = "set limit-uptime=1s";
        } elseif ($expiredMode == "0" && $price != "") {
            $onlogin = ':put (",,' . $price . ',,,noexp,' . $lockMac . ',")' . $lock;
        } else {
            $onlogin = "";
        }

        $bgservice = ':local dateint do={:local montharray ( "jan","feb","mar","apr","may","jun","jul","aug","sep","oct","nov","dec" );
        :local days [ :pick $d 4 6 ];
        :local month [ :pick $d 0 3 ];
        :local year [ :pick $d 7 11 ];
        :local monthint ([ :find $montharray $month]);
        :local month ($monthint + 1);
        :if ( [len $month] = 1) do={:local zero ("0");
        :return [:tonum ("$year$zero$month$days")];}
        else={:return [:tonum ("$year$month$days")];}};
        :local timeint do={ :local hours [ :pick $t 0 2 ];
        :local minutes [ :pick $t 3 5 ]; :return ($hours * 60 + $minutes) ; };
        :local date [ /system clock get date ]; :local time [ /system clock get time ];
        :local today [$dateint d=$date] ; :local curtime [$timeint t=$time] ;
        :foreach i in [ /ip hotspot user find where profile="'.$name.'" ] do={ :local comment [ /ip hotspot user get $i comment];
        :local name [ /ip hotspot user get $i name];
        :local gettime [:pic $comment 12 20];
        :if ([:pic $comment 3] = "/" and [:pic $comment 6] = "/") do={:local expd [$dateint d=$comment] ;
        :local expt [$timeint t=$gettime] ;
        :if (($expd < $today and $expt < $curtime) or ($expd < $today and $expt > $curtime) or ($expd = $today and $expt < $curtime))
        do={ [ /ip hotspot user '.$mode.' $i ]; [ /ip hotspot active remove [find where user=$name] ];}}}';
        $this->hotspotRepository->addUserProfile( $name, $hsPool, $validity, $sharedUsers, $speedLimit, $parent, $onlogin);
        $gsch = $this->hotspotRepository->getSchedulerByHotspotpName($name);
        $monid =$gsch[0]['.id'];
        if($expiredMode != "0") {
            if (empty($monid)) {
                return $this->hotspotRepository->addScheduler($name, $randstarttime, $randinterval, $bgservice);
            } else {
                return $this->hotspotRepository->setScheduler($monid, $name, $randstarttime, $randinterval, $bgservice);
            }
        } else {
            return $this->hotspotRepository->removeScheduller($monid);
        }

    }

//    public function createUserProfile()
//    {
//        $hpName = request()->name;
//        $name = str_replace(' ','', $hpName);
//        $hsPool = request()->address_pool;
//        $sharedUsers = request()->shared_users;
//        $validity = request()->validity;
//        $speedLimit = request()->rate_limit;
//        $amountS = request()->selling_price;
//        $amount = request()->price;
//        $lockMac = request()->lock_user;
//        $parent = request()->parent;
//        $expiredMode = request()->expired_mode;
//        if ($amount == "") {
//            $price = "0";
//        } else {
//            $price = $amount;
//        }
//        if ($amountS == "") {
//            $sprice = "0";
//        } else {
//            $sprice = $amountS;
//        }
//
//        if ($lockMac == "Enable") {
//            $lock = '; [:local mac $"mac-address"; /ip hotspot user set mac-address=$mac [find where name=$user]]';
//        } else {
//            $lock = "";
//        }
//        $randstarttime = "0".rand(1,5).":".rand(10,59).":".rand(10,59);
//        $randinterval = "00:02:".rand(10,59);
//
//        $record = '; :local mac $"mac-address"; :local time [/system clock get time ]; /system script add name="$date-|-$time-|-$user-|-'.$price.'-|-$address-|-$mac-|-' . $validity . '-|-'.$name.'-|-$comment" owner="$month$year" source=$date comment=mikhmon';
//
//        $onlogin = ':put (",'.$expiredMode.',' . $price . ',' . $validity . ','.$sprice.',,' . $lockMac . ',"); {:local date [ /system clock get date ];:local year [ :pick $date 7 11 ];:local month [ :pick $date 0 3 ];:local comment [ /ip hotspot user get [/ip hotspot user find where name="$user"] comment]; :local ucode [:pic $comment 0 2]; :if ($ucode = "vc" or $ucode = "up" or $comment = "") do={ /sys sch add name="$user" disable=no start-date=$date interval="' . $validity . '"; :delay 2s; :local exp [ /sys sch get [ /sys sch find where name="$user" ] next-run]; :local getxp [len $exp]; :if ($getxp = 15) do={ :local d [:pic $exp 0 6]; :local t [:pic $exp 7 16]; :local s ("/"); :local exp ("$d$s$year $t"); /ip hotspot user set comment=$exp [find where name="$user"];}; :if ($getxp = 8) do={ /ip hotspot user set comment="$date $exp" [find where name="$user"];}; :if ($getxp > 15) do={ /ip hotspot user set comment=$exp [find where name="$user"];}; /sys sch remove [find where name="$user"]';
//
//        if ($expiredMode == "rem") {
//            $onlogin = $onlogin . $lock . "}}";
//            $mode = "remove";
//        } elseif ($expiredMode == "ntf") {
//            $onlogin = $onlogin . $lock . "}}";
//            $mode = "set limit-uptime=1s";
//        } elseif ($expiredMode == "remc") {
//            $onlogin = $onlogin . $record . $lock . "}}";
//            $mode = "remove";
//        } elseif ($expiredMode == "ntfc") {
//            $onlogin = $onlogin . $record . $lock . "}}";
//            $mode = "set limit-uptime=1s";
//        } elseif ($expiredMode == "0" && $price != "") {
//            $onlogin = ':put (",,' . $price . ',,,noexp,' . $lockMac . ',")' . $lock;
//        } else {
//            $onlogin = "";
//        }
//
//        $bgservice = ':local dateint do={:local montharray ( "jan","feb","mar","apr","may","jun","jul","aug","sep","oct","nov","dec" );:local days [ :pick $d 4 6 ];:local month [ :pick $d 0 3 ];:local year [ :pick $d 7 11 ];:local monthint ([ :find $montharray $month]);:local month ($monthint + 1);:if ( [len $month] = 1) do={:local zero ("0");:return [:tonum ("$year$zero$month$days")];} else={:return [:tonum ("$year$month$days")];}}; :local timeint do={ :local hours [ :pick $t 0 2 ]; :local minutes [ :pick $t 3 5 ]; :return ($hours * 60 + $minutes) ; }; :local date [ /system clock get date ]; :local time [ /system clock get time ]; :local today [$dateint d=$date] ; :local curtime [$timeint t=$time] ; :foreach i in [ /ip hotspot user find where profile="'.$name.'" ] do={ :local comment [ /ip hotspot user get $i comment]; :local name [ /ip hotspot user get $i name]; :local gettime [:pic $comment 12 20]; :if ([:pic $comment 3] = "/" and [:pic $comment 6] = "/") do={:local expd [$dateint d=$comment] ; :local expt [$timeint t=$gettime] ; :if (($expd < $today and $expt < $curtime) or ($expd < $today and $expt > $curtime) or ($expd = $today and $expt < $curtime)) do={ [ /ip hotspot user '.$mode.' $i ]; [ /ip hotspot active remove [find where user=$name] ];}}}';
//
//        $addHotspotUserProfile = $this->hotspotRepository->addUserProfile(
//            $name,
//            $hsPool,
//            $validity,
//            $sharedUsers,
//            $speedLimit,
//            $parent,
//            $onlogin
//        );
//        $monid = $this->hotspotRepository->getSchedulerByHotspotpName($name);
//        if($expiredMode != "0") {
//            if (empty($monid)) {
//               return $qsch = $this->hotspotRepository->addScheduler($name, $randstarttime, $randinterval, $bgservice);
//            } else {
//               return $qset = $this->hotspotRepository->setScheduler($monid, $name, $randstarttime, $randinterval, $bgservice);
//            }
//        } else {
//           return $qrmv = $this->hotspotRepository->removeScheduller($monid);
//        }
//    }

}
